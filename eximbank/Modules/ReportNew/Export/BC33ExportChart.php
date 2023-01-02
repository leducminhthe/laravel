<?php
namespace Modules\ReportNew\Export;

use App\Models\Config;
use App\Models\LogoModel;
use App\Models\Categories\Titles;

use App\Scopes\CompanyScope;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\ReportNew\Entities\BC33;
use Modules\Survey\Entities\Survey;
use Modules\Survey\Entities\SurveyQuestion;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Legend as ChartLegend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class BC33ExportChart implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithTitle, WithCharts
{
    use Exportable;

    protected $index = 0;
    protected $count = 0;
    protected $column = 1;

    public function __construct($survey_id, $question, $no)
    {
        $this->survey_id = $survey_id;
        $this->title = $question->name;
        $this->no = $no;
        $this->question = $question;
    }

    public function title(): string
    {
        return 'Chart câu hỏi '.$this->no;
    }

    public function query()
    {
        $query = BC33::sql($this->survey_id)->orderBy('id', 'DESC');

        $this->count = $query->count();
        return $query;
    }

    public function map($row): array
    {
        $obj = [];
        return $obj;
    }

    public function headings(): array
    {
        return [[], [], [], [],  ['BÁO CÁO KẾT QUẢ KHẢO SÁT'], []];
    }

    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
            $title = ['font' => ['size' => 12, 'name' => 'Arial', 'bold' => true,], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,],];

            $event->sheet->getDelegate()->mergeCells('A5:T5')->getStyle('A5')->applyFromArray($title);

        },

        ];
    }


    public function charts()
    {

        if($this->question->type=='choice')
            return $this->lineCharts();
        else return $this->pieCharts();
    }

    public function lineCharts()
    {
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet->fromArray(
            [
                ['', 2010, 2011, 2012],
                ['Q1', 12, 15, 21],
                ['Q2', 56, 73, 86],
                ['Q3', 52, 61, 69],
                ['Q4', 30, 32, 0],
            ]
        );

    // Set the Labels for each data series we want to plot
    //     Datatype
    //     Cell reference for data
    //     Format Code
    //     Number of datapoints in series
    //     Data values
    //     Data Marker
            $dataSeriesLabels = [
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$1', null, 1), // 2010
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$C$1', null, 1), // 2011
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$D$1', null, 1), // 2012
            ];
    // Set the X-Axis Labels
    //     Datatype
    //     Cell reference for data
    //     Format Code
    //     Number of datapoints in series
    //     Data values
    //     Data Marker
            $xAxisTickValues = [
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$2:$A$5', null, 4), // Q1 to Q4
            ];
    // Set the Data values for each data series we want to plot
    //     Datatype
    //     Cell reference for data
    //     Format Code
    //     Number of datapoints in series
    //     Data values
    //     Data Marker
            $dataSeriesValues = [
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$B$2:$B$5', null, 4),
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$C$2:$C$5', null, 4),
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$D$2:$D$5', null, 4),
            ];
            $dataSeriesValues[2]->setLineWidth(60000);

    // Build the dataseries
            $series = new DataSeries(
                DataSeries::TYPE_LINECHART, // plotType
                DataSeries::GROUPING_STACKED, // plotGrouping
                range(0, count($dataSeriesValues) - 1), // plotOrder
                $dataSeriesLabels, // plotLabel
                $xAxisTickValues, // plotCategory
                $dataSeriesValues        // plotValues
            );

    // Set the series in the plot area
            $plotArea = new PlotArea(null, [$series]);
    // Set the chart legend
            $legend = new ChartLegend(ChartLegend::POSITION_TOPRIGHT, null, false);

            $title = new Title($this->title);
            $yAxisLabel = new Title('Phần trăm người chọn');

    // Create the chart
            $chart = new Chart(
                'chart'.$this->no, // name
                $title, // title
                $legend, // legend
                $plotArea, // plotArea
                true, // plotVisibleOnly
                DataSeries::EMPTY_AS_GAP, // displayBlanksAs
                null, // xAxisLabel
                $yAxisLabel  // yAxisLabel
            );

    // Set the position where the chart should appear in the worksheet
            $chart->setTopLeftPosition('A7');
            $chart->setBottomRightPosition('H20');
            return $chart;
    }

    public function pieCharts()
    {
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet->fromArray(
            [
                ['', 2010, 2011, 2012],
                ['Q1', 12, 15, 21],
                ['Q2', 56, 73, 86],
                ['Q3', 52, 61, 69],
                ['Q4', 30, 32, 0],
            ]
        );

// Custom colors for dataSeries (gray, blue, red, orange)
        $colors = [
            'cccccc', '00abb8', 'b8292f', 'eb8500',
        ];

// Set the Labels for each data series we want to plot
//     Datatype
//     Cell reference for data
//     Format Code
//     Number of datapoints in series
//     Data values
//     Data Marker
        $dataSeriesLabels1 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$C$1', null, 1), // 2011
        ];
// Set the X-Axis Labels
//     Datatype
//     Cell reference for data
//     Format Code
//     Number of datapoints in series
//     Data values
//     Data Marker
        $xAxisTickValues1 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$2:$A$5', null, 4), // Q1 to Q4
        ];
// Set the Data values for each data series we want to plot
//     Datatype
//     Cell reference for data
//     Format Code
//     Number of datapoints in series
//     Data values
//     Data Marker
//     Custom colors
        $dataSeriesValues1 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$C$2:$C$5', null, 4, [], null, $colors),
        ];

// Build the dataseries
        $series1 = new DataSeries(
            DataSeries::TYPE_PIECHART, // plotType
            null, // plotGrouping (Pie charts don't have any grouping)
            range(0, count($dataSeriesValues1) - 1), // plotOrder
            $dataSeriesLabels1, // plotLabel
            $xAxisTickValues1, // plotCategory
            $dataSeriesValues1          // plotValues
        );

// Set up a layout object for the Pie chart
        $layout1 = new Layout();
        $layout1->setShowVal(true);
        $layout1->setShowPercent(true);

// Set the series in the plot area
        $plotArea1 = new PlotArea($layout1, [$series1]);
// Set the chart legend
        $legend1 = new ChartLegend(ChartLegend::POSITION_RIGHT, null, false);

        $title1 = new Title($this->title);

// Create the chart
        $chart1 = new Chart(
            'chart'.$this->no, // name
            $title1, // title
            $legend1, // legend
            $plotArea1, // plotArea
            true, // plotVisibleOnly
            DataSeries::EMPTY_AS_GAP, // displayBlanksAs
            null, // xAxisLabel
            null   // yAxisLabel - Pie charts don't have a Y-Axis
        );

// Set the position where the chart should appear in the worksheet
        $chart1->setTopLeftPosition('A7');
        $chart1->setBottomRightPosition('H20');
        return $chart1;
    }

}
