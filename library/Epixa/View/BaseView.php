<?php
/**
 * Epixa Library
 */

namespace Epixa\View;

use Zend_View_Exception as ViewException;

/**
 * Extension of Zend_View that changes the way base paths are stored.
 *
 * @category  Epixa
 * @package   View
 * @copyright 2010 epixa.com - Court Ewing
 * @license   http://github.com/epixa/Epixa/blob/master/LICENSE New BSD
 * @author    Court Ewing (court@epixa.com)
 */
class BaseView extends \Zend_View
{
    /**
     * Given a base path, add a template directory.
     *
     * Helper and filter directories are not set as they should be handled by
     * individual bootstraps.
     *
     * @param  string $path
     * @param  string $prefix Ignored
     * @return BaseView *Fluent interface*
     * @throws ViewException If path is invalid
     */
    public function addBasePath($path, $classPrefix = 'Zend_View')
    {
        $realPath = realpath($path);
        if ($realPath === false) {
            throw new ViewException(sprintf(
                'Path `%s` does not exist', $path
            ));
        }

        $realPath  = rtrim($realPath, '/');
        $realPath  = rtrim($realPath, '\\');
        $realPath .= DIRECTORY_SEPARATOR;

        $this->addScriptPath($realPath . 'templates');

        return $this;
    }
}