<?php namespace Immap\Watchkeeper\Support\FormBuilder;
use \Illuminate\Routing\UrlGenerator as UrlGenerator;
use \Illuminate\Session\Store as Session;
use \Illuminate\Html\HtmlBuilder as HtmlBuilder;
use \Illuminate\Html\FormBuilder as FormBuilder;
use \Symfony\Component\Translation\TranslatorInterface as TranslatorInterface;
class WKFormBuilder extends \Illuminate\Html\FormBuilder {

    /**
     * The Translator implementation.
     *
     * @var Symfony\Component\Translation\TranslatorInterface
     */
    protected $translator;

    /**
     * Create a new form builder instance.
     *
     * @param  \Illuminate\Routing\UrlGenerator  $url
     * @param  \Illuminate\Html\HtmlBuilder  $html
     * @param  string  $csrfToken
     * @param  \Symfony\Component\Translation\TranslatorInterface  $translator
     * @return void
     */
    public function __construct(HtmlBuilder $html, UrlGenerator $url, $csrfToken, TranslatorInterface $translator)
    {
        $this->url = $url;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->translator = $translator;
    }

    /**
     * Create a select box field.
     *
     * @param  string  $name
     * @param  array   $list
     * @param  string  $selected
     * @param  array   $options
     * @return string
     */
    public function select($name, $list = array(), $selected = null, $options = array())
    {
        // When building a select box the "value" attribute is really the selected one
        // so we will use that when checking the model or session for a value which
        // should provide a convenient method of re-populating the forms on post.
        $selected = $this->getValueAttribute($name, $selected);

        $options['id'] = $this->getIdAttribute($name, $options);

        $options['name'] = $name;

        // Split the _prepend option out of the options array, if it exists.
        if (isset($options['_prepend']))
        {
            $list = array_pull($options, '_prepend') + $list;
        }

        // We will simply loop through the options and build an HTML value for each of
        // them until we have an array of HTML declarations. Then we will join them
        // all together into one single HTML element that can be put on the form.
        $html = array();

        foreach ($list as $value => $display)
        {
            $html[] = $this->getSelectOption($display, $value, $selected);
        }

        // Once we have all of this HTML, we can join this into a single element after
        // formatting the attributes into an HTML "attributes" string, then we will
        // build out a final select statement, which will contain all the values.
        $options = $this->html->attributes($options);

        $list = implode('', $html);

        return "<select{$options}>{$list}</select>";
    }

    /**
     * Create a select month field.
     *
     * @param  string  $name
     * @param  string  $selected
     * @param  array   $options
     * @return string
     */
    public function selectMonth($name, $selected = null, $options = array())
    {
        $months = array();

        foreach (range(1, 12) as $month)
        {
            $months[$month] = strftime('%B', mktime(0, 0, 0, $month, 1));

            $key = 'datetime.'.strtolower($months[$month]);
            if ($this->translator->has($key))
            {
                $months[$month] = $this->translator->trans($key);
            }
        }

        return $this->select($name, $months, $selected, $options);
    }

    /**
     * Create a select day of the week field.
     *
     * @param  string  $name
     * @param  string  $selected
     * @param  array   $options
     * @return string
     */
    public function selectWeekday($name, $selected = null, $options = array())
    {
        $days = array();
        $day_names = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');

        foreach (range(1, 7) as $day)
        {
            $days[$day] = $day_names[$day - 1];

            $key = 'datetime.'.strtolower($days[$day]);
            if ($this->translator->has($key))
            {
                $days[$day] = $this->translator->trans($key);
            }
        }

        return $this->select($name, $days, $selected, $options);
    }

    /**
     * Create a datetime-local input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
    public function jqmDateTime($name, $value = null, $options = array())
    {
        return $this->input('datetime', $name, $value, $options);
    }

    /**
     * Create a datetime-local input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
    public function jqmDateTimeLocal($name, $value = null, $options = array())
    {
        return $this->input('datetime-local', $name, $value, $options);
    }

    /**
     * Create a datetime-local input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
    public function jqmNumber($name, $value = null, $options = array())
    {
        return $this->input('number', $name, $value, $options);
    }
    /**
     * Create a datetime-local input field.
     *
     * @param  array  $params
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
    public function jqmPaginationRoute(array $params)
    {
        extract($params);
        $htmlString = '';
        if (isset($previousPage) && isset($nextPage))
        {
            $htmlString  = '<div class="ui-grid-a">';
            $htmlString .= $this->html->linkRoute($url, 'Previous', array('page' => $previousPage) , array('class' => "ui-block-a ui-btn ui-icon-arrow-l ui-btn-icon-left"));
            $htmlString .= $this->html->linkRoute($url, 'Next', array('page' => $nextPage), array('class' => "ui-block-b ui-btn ui-icon-arrow-r ui-btn-icon-right"));
            $htmlString .= '</div>';
        } elseif (!isset($previousPage) || !isset($nextPage)) {
            $htmlString  = '<div class="ui-grid-solo">';
            $htmlString .= isset($previousPage) ? $this->html->linkRoute($url, 'Previous', array('page' => $previousPage) , array('class' => "ui-btn ui-icon-arrow-l ui-btn-icon-left")) : '';
            $htmlString .= isset($nextPage) ? $this->html->linkRoute($url, 'Next', array('page' => $nextPage), array('class' => "ui-btn ui-icon-arrow-r ui-btn-icon-right") ) : '';
            $htmlString .= '</div>';
        }
        return $htmlString;
    }
}
