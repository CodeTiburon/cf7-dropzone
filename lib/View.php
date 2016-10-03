<?php


namespace CodeTiburon\Wordbox;

use ArrayAccess;

/**
 * Provides the “View” layer of Wordbox Plugin Framework
 *
 * @author CodeTiburon
 */
class View {
    
    /**
     * Template being rendered
     *
     * @var null|string
     */
    private $__template = null;

    /**
     * @var ArrayAccess|array ArrayAccess or associative array representing available variables
     */
    private $__vars;
    
    /**
     * Script file name to execute
     *
     * @var string
     */
    private $__file = null;
    
    /**
     * @var string Rendered content
     */
    private $__content = '';
    
    
    /**
     * Set variable storage
     *
     * Expects either an array, or an object implementing ArrayAccess.
     *
     * @param  array|ArrayAccess $variables
     * @return View
     * @throws Exception
     */
    public function setVars($variables = [])
    {
        if (!is_array($variables) && !$variables instanceof \ArrayAccess) {
            throw new Exception(sprintf(
                'Expected array or ArrayAccess object; received "%s"',
                (is_object($variables) ? get_class($variables) : gettype($variables))
            ));
        }
        
        // Enforce a ArrayObject container
        if (!$variables instanceof \ArrayObject) {
            $variablesAsArray = [];
            foreach ($variables as $key => $value) {
                $variablesAsArray[$key] = $value;
            }
            $variables = new \ArrayObject(
                    $variablesAsArray, 
                    \ArrayObject::ARRAY_AS_PROPS,
                    'ArrayIterator');
        }

        
        $this->__vars = $variables;
        return $this;
    }
    
    /**
     * Get a single variable, or all variables
     *
     * @param  mixed $key
     * @return mixed
     */
    public function vars($key = null)
    {
        if (null === $this->__vars) {
            $this->setVars();
        }
        if (null === $key) {
            return $this->__vars;
        }
        return $this->__vars[$key];
    }
    
    /**
     * Overloading: proxy to ArrayObject
     *
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        $vars = $this->vars();
        return $vars[$name];
    }
    /**
     * Overloading: proxy to ArrayObject
     *
     * @param  string $name
     * @param  mixed $value
     * @return void
     */
    public function __set($name, $value)
    {
        $vars = $this->vars();
        $vars[$name] = $value;
    }
    /**
     * Overloading: proxy to ArrayObject
     *
     * @param  string $name
     * @return bool
     */
    public function __isset($name)
    {
        $vars = $this->vars();
        return isset($vars[$name]);
    }
    /**
     * Overloading: proxy to ArrayObject
     *
     * @param  string $name
     * @return void
     */
    public function __unset($name)
    {
        $vars = $this->vars();
        if (!isset($vars[$name])) {
            return;
        }
        unset($vars[$name]);
    }
    
    /**
     * Retrieve the filesystem path to a template
     * @param string $templateName
     * @return string file path
     */
    private function _resolve($templateName)
    {
        $parts = explode('/', $templateName);
        array_splice($parts, 1, 0, 'templates');
        return plugin_dir_path( dirname( __FILE__ ) ) . join(DIRECTORY_SEPARATOR, $parts) . '.php';
    }

    /**
     * Set template
     * @param string $templateName
     * @return \CodeTiburon\Wordbox\View
     */
    public function setTemplate($templateName)
    {
        $this->__template = $templateName;
        return $this;
    }
    
    /**
     * Get template name
     * @return string
     */
    public function getTemplate() 
    {
        return $this->__template;
    }

    /**
     * Processes a template and returns the output.
     * @param string $templateName
     * @param  array|ArrayAccess $values
     * @return string html content
     */
    public function render($templateName = null, $values = null)
    {
        if (null !== $values) {
            $this->setVars($values);
        }
        unset($values);
        
        if ($templateName) {
            $this->setTemplate($templateName);
        }
        unset($templateName);
        
        // extract all assigned vars (pre-escaped), but not 'this'.
        // assigns to a double-underscored variable, to prevent naming collisions
        $__vars = $this->vars()->getArrayCopy();
        if (array_key_exists('this', $__vars)) {
            unset($__vars['this']);
        }
        extract($__vars);
        unset($__vars); // remove $__vars from local scope
        
        try {
            $this->__file = $this->_resolve($this->__template);
            ob_start();
            $includeReturn = include $this->__file;
            $this->__content = ob_get_clean();
        } catch (\Exception $ex) {
            ob_end_clean();
            throw $ex;
        }
        if ($includeReturn === false && empty($this->__content)) {
            throw new \Exception(sprintf(
                '%s: Unable to render template "%s"; file include failed',
                __METHOD__,
                $this->__file
            ));
        }
        
        return $this->__content;
    }
    
    /**
     * Make sure View variables are cloned when the view is cloned.
     *
     * @return View
     */
    public function __clone()
    {
        $this->__vars = clone $this->vars();
    }
    
    /**
     * Get the string contents of the view.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
    
    /**
     * Processes a template and prints the output.
     * @param string $templateName
     * @param array $variables
     * @param boolean $output
     * @return string HTML
     */
    public static function make($templateName, $variables = null, $output = true)
    {
        $view = new self();
        $content = $view->render($templateName, $variables);
        if ($output) {
            echo $content;
        }
        return $content;
    }
}
