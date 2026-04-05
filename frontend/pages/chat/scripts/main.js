document.addEventListener('DOMContentLoaded', () => {
  const msgContainer = document.getElementById('chat-messages');
  const textarea = document.getElementById('message-content');
  const form = document.getElementById('message-form');

  // Scroll en bas au chargement
  setTimeout(() => { msgContainer.scrollTop = msgContainer.scrollHeight; }, 0);

  // Auto-resize textarea
  textarea.addEventListener('input', () => {
    textarea.style.height = '48px';
    textarea.style.height = textarea.scrollHeight + 'px';
  });

  // Ratchet WebSocket (Demo URL)
  const conn = new WebSocket('wss://intuitive-imagination-production-8f76.up.railway.app');

  conn.onopen = () => {
    console.log("Connecté!");
  };

  conn.onmessage = (e) => {
    ajouterMessage(e.data, 'receiver');
  };

  conn.onerror = (e) => {
    console.log("Erreur WebSocket:", e);
  };

  // Envoyer un message
  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const texte = textarea.value.trim();
    if (!texte) return;

    conn.send(texte);
    ajouterMessage(texte, 'sender');
    textarea.value = '';
    textarea.style.height = '48px';
    
    // Reset reply status
    if (window.ChatUI) ChatUI.cancelReply();
  });

  function ajouterMessage(texte, type) {
    const now = new Date();
    const dateStr = `${now.getHours()}h${String(now.getMinutes()).padStart(2, '0')}`;
    const timestamp = now.toISOString();
    const msgId = Date.now();

    const wrapper = document.createElement('div');
    wrapper.className = `message-wrapper ${type === 'sender' ? 'self-end items-end group' : 'self-start items-start group'}`;
    wrapper.setAttribute('data-id', msgId);
    wrapper.setAttribute('data-time', timestamp);

    const isSender = type === 'sender';
    
    // Add Reply context if we are replying
    let replyHtml = '';
    if (isSender && window.ChatUI && ChatUI.replyingTo) {
        const quotedMsg = document.getElementById('reply-text').textContent;
        replyHtml = `<div class="reply-preview text-white opacity-70 border-white/30">${quotedMsg}</div>`;
    }

    const actionsHtml = `
      <div class="message-actions text-slate-500">
        <span class="material-symbols-outlined action-icon" title="Reply" onclick="ChatUI.setReply(${msgId})">reply</span>
        <span class="material-symbols-outlined action-icon" title="React" onclick="ChatUI.toggleReactionMenu(${msgId})">add_reaction</span>
        ${isSender ? `
          <span class="material-symbols-outlined action-icon" title="Edit" onclick="ChatUI.editMessage(${msgId})">edit</span>
          <span class="material-symbols-outlined action-icon text-error/70" title="Delete" onclick="ChatUI.deleteMessage(${msgId})">delete</span>
        ` : ''}
      </div>
    `;

    wrapper.innerHTML = `
      <div class="${type}">
        ${replyHtml}
        <div class="content-msg">${texte}</div>
        <span class="date">${dateStr}</span>
      </div>
      ${actionsHtml}
    `;

    msgContainer.appendChild(wrapper);
    msgContainer.scrollTop = msgContainer.scrollHeight;
  }

});
