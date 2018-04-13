<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;

/*
 *  There are a few methods you can use in all charts (regardless of the charting library). These includes:
 *
 *  dataset(string $name, string $type, $data) - Adds a new dataset to the chart.
 *  labels($labels) - Set the chart labels.
 *  options($options, bool $overwrite = false) - Set the chart options.
 *  container(string $container = null) - Set the chart container (if you need your own view). Display the view if no parameter is set.
 *  script(string $script = null) - Set the chart script. (if you need your own view). Display the view if no parameter is set.
 *  type(string $type) - Force a chart type, if not set, the first dataset type found will be used.
 *  height(int $height) - Set the chart height. Null / 0 by default = auto
 *  width(int $width) - Set the chart width. Null / 0 by default = auto
 *  loader(bool $loader) - Determines if the chart loader should be displayed. Default to true
 *  loaderColor(string $color) - Set the chart loader color. Default: #22292F
 *  reset() - Reset the chart options.
 */
class SampleChart extends Chart
{
    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->labels(['One', 'Two', 'Three', 'Four', 'Five']);
        $this->displayLegend(false);                // chart configuration presets
/*            ->options([
                'legend' => [
                    'display' => false  // Esconda la leyenda.
                ]
            ]);*/
    }
}
