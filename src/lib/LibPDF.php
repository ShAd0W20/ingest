<?php

/** 
 * LibPDF.php
 *
 * Llibreria d'utilitats per a la impresió en PDF.
 *
 * @author Josep Ciberta
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

require_once(ROOT.'/vendor/TCPDF/tcpdf.php');

/**
 * Classe base per a la generació de documents PDF amb mètodes bàsics.
 */
class DocumentPDF extends TCPDF 
{
	/**
	 * FontFamily desada.
	 * @private
	 */
	private $FontFamilyDesada = 'helvetica';

	/**
	 * FontStyle desat.
	 * @private
	 */
	private $FontStyleDesat = '';

	/**
	 * FontSizePt desat.
	 * @private
	 */
	private $FontSizePtDesat = 12;

	public function __construct($orientation='P', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8', $diskcache=false, $pdfa=false) {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);

		// set document information
		$this->SetCreator(PDF_CREATOR);
		$this->SetAuthor('Institut de Palamós');
		$this->SetKeywords('INS Palamós, Palamós');

		// set default header data
		$this->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

		// set header and footer fonts
		$this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$this->SetMargins(PDF_MARGIN_LEFT, 120, PDF_MARGIN_RIGHT);
		$this->SetHeaderMargin(PDF_MARGIN_HEADER);
		$this->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$this->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set font
		$this->SetFont('helvetica', '', 10);
    }

	/**
	 * Desa els valors de la font actual.
	 */
	private function DesaFont() {
		$this->FontFamilyDesada = $this->FontFamily;
		$this->FontStyleDesat = $this->FontStyle;
		$this->FontSizePtDesat = $this->FontSizePt;
	}

	/**
	 * Restaura els valors desats per a la font.
	 */
	private function RestauraFont() {
		$this->SetFont($this->FontFamilyDesada, $this->FontStyleDesat, $this->FontSizePtDesat);
	}

	/**
	 * Escriu un text.
	 * @param string $Text Text a mostrar.
	 * @param int $Mida Mida de la font.
	 * @param int $IncrementY Increment de l'eix Y.
	 */
	public function Escriu($Text, int $Mida = 12, int $IncrementY = 8) {
		$this->DesaFont();
		//$this->SetX($this->original_lMargin);
        $this->SetFont('helvetica', '', $Mida); 
		$this->Cell(($this->w - $this->original_lMargin - $this->original_rMargin), 0, $Text, '', 0, 'L'); 
		//$this->SetY($this->GetY() + $IncrementY);
		$this->RestauraFont();
	}

	/**
	 * Escriu una línea.
	 * @param string $Text Text a mostrar.
	 * @param int $Mida Mida de la font.
	 * @param int $IncrementY Increment de l'eix Y.
	 */
	public function EscriuLinia($Text, int $Mida = 12, int $IncrementY = 8) {
		$this->DesaFont();
		$this->SetX($this->original_lMargin);
        $this->SetFont('helvetica', '', $Mida); 
//		$this->Cell(($this->w - $this->original_lMargin - $this->original_rMargin), 0, utf8_encodeX($Text), '', 0, 'L'); 
		$this->Cell(($this->w - $this->original_lMargin - $this->original_rMargin), 0, $Text, '', 0, 'L'); 
		$this->SetY($this->GetY() + $IncrementY);
		$this->RestauraFont();
	}

	/**
	 * Títol 1 per als expedients de notes.
	 * @param string $Titol Títol a mostrar.
	 * @param int $Mida Mida de la font.
	 * @param int $IncrementY Increment de l'eix Y.
	 */
	public function Titol1(string $Titol, int $Mida = 14, int $IncrementY = 10) {
		$this->DesaFont();
		$this->SetX($this->original_lMargin);
        $this->SetFont('helvetica', 'B', $Mida); // Bold
		$this->SetLineStyle(array('width' => 0.85 / $this->k, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0));
//		$this->Cell(($this->w - $this->original_lMargin - $this->original_rMargin), 0, utf8_encodeX($Titol), 'B', 0, 'L'); // B: Bottom
		$this->Cell(($this->w - $this->original_lMargin - $this->original_rMargin), 0, $Titol, 'B', 0, 'L'); // B: Bottom
		$this->SetY($this->GetY() + $IncrementY);
		$this->RestauraFont();
	}

	/**
	 * Títol 2 per als expedients de notes.
	 * @param string $Titol Títol a mostrar.
	 * @param int $Mida Mida de la font.
	 * @param int $IncrementY Increment de l'eix Y.
	 */
	public function Titol2($Titol, int $Mida = 12, int $IncrementY = 8) {
		$this->DesaFont();
		$this->SetX($this->original_lMargin);
        $this->SetFont('helvetica', 'B', $Mida);
		$this->SetLineStyle(array('width' => 0.85 / $this->k, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0));
//		$this->Cell(($this->w - $this->original_lMargin - $this->original_rMargin), 0, utf8_encodeX($Titol), 'B', 0, 'L'); // B: Bottom
		$this->Cell(($this->w - $this->original_lMargin - $this->original_rMargin), 0, $Titol, 'B', 0, 'L'); // B: Bottom
		$this->SetY($this->GetY() + $IncrementY);
		$this->RestauraFont();
	}
	
	/**
	 * Dicuixa una línia a sota, estil subratllat.
	 * @param float $Gruix Gruix de la línia.
	 */
	public function Linia(float $Gruix = 0.85) {
		$this->SetX($this->original_lMargin);
		$this->SetLineStyle(array('width' => $Gruix / $this->k, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0));
		$this->Cell(($this->w - $this->original_lMargin - $this->original_rMargin), 0, '', 'B', 0, 'L'); // B: Bottom
	}	

	/**
	 * Escriu text per a 5 columnes.
	 * @param string $Text1 Text per a la 1a columna.
	 * @param string $Text2 Text per a la 2a columna.
	 * @param string $Text3 Text per a la 3a columna.
	 * @param string $Text4 Text per a la 4a columna.
	 * @param string $Text5 Text per a la 5a columna.
	 */
	public function Encolumna5($Text1, $Text2 = '', $Text3 = '', $Text4 = '', $Text5 = '') {
		$Pas = 38;
		$this->SetX($this->original_lMargin);
        $this->SetFont('helvetica', '', 12); // Helvetica, 12

		$this->SetX($this->original_lMargin);
		$this->writeHTML($Text1, False);
		$this->SetX($this->original_lMargin + 1*$Pas);
		$this->writeHTML($Text2, False);
		$this->SetX($this->original_lMargin + 2*$Pas);
		$this->writeHTML($Text3, False);
		$this->SetX($this->original_lMargin + 3*$Pas);
		$this->writeHTML($Text4, False);
		$this->SetX($this->original_lMargin + 4*$Pas);
		$this->writeHTML($Text5, False);

/*		$this->SetX($this->original_lMargin);
		$this->writeHTML(utf8_encodeX($Text1), False);
		$this->SetX($this->original_lMargin + 1*$Pas);
		$this->writeHTML(utf8_encodeX($Text2), False);
		$this->SetX($this->original_lMargin + 2*$Pas);
		$this->writeHTML(utf8_encodeX($Text3), False);
		$this->SetX($this->original_lMargin + 3*$Pas);
		$this->writeHTML(utf8_encodeX($Text4), False);
		$this->SetX($this->original_lMargin + 4*$Pas);
		$this->writeHTML(utf8_encodeX($Text5), False);
*/
		$this->SetY($this->GetY() + 6);
	}
}

?>