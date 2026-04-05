<?php
/**
 * XSSProtection.php
 * Nettoyage et validation des entrées pour prévenir les attaques XSS.
 */
class XSSProtection
{
    /**
     * Nettoie une chaîne de caractères (supprime les balises)
     */
    public static function sanitize($data)
    {
        if (is_array($data)) {
            return array_map([self::class, 'sanitize'], $data);
        }
        return htmlspecialchars(strip_tags((string)$data), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Valide le contenu d'un post
     */
    public static function validatePostContent($content)
    {
        $content = trim($content);
        
        if (empty($content)) {
            return array('valid' => false, 'error' => 'Le contenu ne peut pas être vide.');
        }

        if (strlen($content) > 2000) {
            return array('valid' => false, 'error' => 'Le contenu est trop long (max 2000 caractères).');
        }

        return array('valid' => true, 'content' => self::sanitize($content));
    }
}
