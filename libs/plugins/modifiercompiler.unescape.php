<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsModifierCompiler
 */
/**
 * Smarty unescape modifier plugin
 * Type:     modifier
 * Name:     unescape
 * Purpose:  unescape html entities
 *
 * @author Rodney Rehm
 *
 * @param array $params parameters
 * @param Smarty_Internal_TemplateCompilerBase $compiler
 *
 * @return string with compiled code
 */
function smarty_modifiercompiler_unescape($params, Smarty_Internal_TemplateCompilerBase $compiler)
{
    $compiler->template->_checkPlugins(
        array(
            array(
                'function' => 'smarty_literal_compiler_param',
                'file'     => SMARTY_PLUGINS_DIR . 'shared.literal_compiler_param.php'
            )
        )
    );

    $esc_type = smarty_literal_compiler_param($params, 1, 'html');

    if (!isset($params[ 2 ])) {
        $params[ 2 ] = '\'' . addslashes(Smarty::$_CHARSET) . '\'';
    }

    $params[ 0 ] = (string)$params[ 0 ];
    switch ($esc_type) {
        case 'entity':
        case 'htmlall':
            if (Smarty::$_MBSTRING) {
                return 'html_entity_decode(htmlspecialchars_decode(mb_convert_encoding(' . $params[ 0 ] . ', ' . $params[ 2 ] . ')))'; // PHP 8.2 sets use of HTML-ENTITIES deprecated
            }
            return 'html_entity_decode(' . $params[ 0 ] . ', ENT_NOQUOTES, ' . $params[ 2 ] . ')';
        case 'html':
            return 'htmlspecialchars_decode(' . $params[ 0 ] . ', ENT_QUOTES)';
        case 'url':
            return 'rawurldecode(' . $params[ 0 ] . ')';
        default:
            return $params[ 0 ];
    }
}
