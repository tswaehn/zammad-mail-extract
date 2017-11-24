<?php 

/**
 * HTML Renderer
 * 
 *
 */
class HTML {
	
	protected $title = 'Zammad Mailer';
	protected $subTitle = 'Home';
	
	protected $content = '';
	
	public function __construct() {
		
	}
	
	
	/**
	 * render entire site
	 * 
	 * @return String - HTML code whole page
	 */
	public function render() {
		
		$html = '<!DOCTYPE html>
				<html>';
		$html.= '<head>';
		$html.= '<meta charset="utf-8">';
		$html.= '<title>'.$this->title.' - '.$this->subTitle.'</title>';
		$html.= '<script language="javascript" src="./lib/jquery-3.2.1.min.js"></script>';
		$html.= '<script type="text/javascript" src="./lib/jquery.dataTables.min.js"></script>';
		$html.= '<link rel="stylesheet" type="text/css" href="./lib/jquery.dataTables.min.css" />';
		$html.= '</head>';
		$html.= '<body>';
		$html.= $this->content;
		$html.= '<br /><br /><a href="index.php">back</a>';
		$html.= '</body>';
		$html.= '</html>';
		
		return $html;
	}
	
	/**
	 * add content to html body
	 * 
	 * @param String $content - html content
	 */
	public function addContent($content, $nl = false) {
		$this->content.= $content;
		if ($nl) {
			$this->content.= '<br />';
		}
	}
	
	/**
	 * set title for current page
	 */
	public function setTitle($title) {
		$this->subTitle = $title;
	}
}


?>