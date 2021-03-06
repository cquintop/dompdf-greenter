<?php

namespace Webmaster\Report;

use Greenter\Model\DocumentInterface;
use Greenter\Report\ReportInterface;
use \Dompdf\Dompdf;

/**
 * Class DompdfReport.
 */
class DompdfReport implements ReportInterface
{
    /**
     * @var ReportInterface
     */
    private $htmlReport;

    /**
     * @var string
     */
    private $html;

    /**
     * @var Dompdf
     */
    private $pdfRender;

    /**
     * DompdfReport constructor.
     *
     * @param ReportInterface $htmlReport
     */
    public function __construct(ReportInterface $htmlReport)
    {
        $this->htmlReport = $htmlReport;
        $options = new \Dompdf\Options();
        $options->setDpi(112);
        $options->setDefaultPaperSize('A4');
        $options->setDefaultMediaType('print');
        $options->setIsFontSubsettingEnabled(true);
        $options->setFontHeightRatio(0.7);
        
        $this->pdfRender = new Dompdf($options);
    }

    /**
     * Return last html generated.
     *
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param DocumentInterface $document
     * @param array             $parameters
     *
     * @return mixed
     */
    public function render(DocumentInterface $document, $parameters = [])
    {
        $this->html = $this->htmlReport->render($document, $parameters);

        return $this->buildPdf($this->html);
    }

    /**
     * Dompdf Options.
     *
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->pdfRender->set_options($options);
    }

    /**
     * @return Dompdf
     */
    public function getExporter()
    {
        return $this->pdfRender;
    }

   

    private function buildPdf($html)
    {
        $this->pdfRender->loadHtml($html);
        $this->pdfRender->render();
        return $this->pdfRender->output();
    }
    
}
