<?
namespace Concrete\Block\GoogleMap;
use Loader;
use Page;
use \Concrete\Core\Block\BlockController;
class Controller extends BlockController {
	
	protected $btTable = 'btGoogleMap';
	protected $btInterfaceWidth = "400";
	protected $btInterfaceHeight = "320";
	protected $btCacheBlockRecord = true;
	protected $btCacheBlockOutput = true;
	protected $btCacheBlockOutputOnPost = true;
	protected $btCacheBlockOutputForRegisteredUsers = false;

	public $title = "";
	public $location = "";
	public $latitude = "";
	public $longitude = "";
	public $zoom = 14;								
	
	/** 
	 * Used for localization. If we want to localize the name/description we have to include this
	 */
	public function getBlockTypeDescription() {
		return t("Enter an address and a Google Map of that location will be placed in your page.");
	}
		
	public function getBlockTypeName() {
		return t("Google Map");
	}		
	
	
	public function validate($args) {
		$error = Loader::helper('validation/error');
		
		if(empty($args['location']) ||  $args['latitude'] === '' || $args['longtitude'] === '') {
			$error->add(t('You must select a valid location.'));
		}	
		
		if(!is_numeric($args['zoom'])) {
			$error->add(t('Please enter a zoom number from 0 to 21.'));
		}
		
		if($error->has()) {
			return $error;
		}
	}
	
	
	public function view(){
        $this->requireAsset('javascript', 'jquery');
		$this->set('bID', $this->bID);	
		$this->set('title', $this->title);
		$this->set('location', $this->location);
		$this->set('latitude', $this->latitude);
		$this->set('longitude', $this->longitude);
		$this->set('zoom', $this->zoom);			
		$html = Loader::helper('html');
		$c = Page::getCurrentPage();
		if (!$c->isEditMode()) {
			$this->addFooterItem('<script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=true"></script>');
			$this->addFooterItem('<script type="text/javascript"> 
			function googleMapInit' . $this->bID . '() { 
			   try{
				  var latlng = new google.maps.LatLng(' . $this->latitude . ', ' . $this->longitude . ');
				   var mapOptions = {
					 zoom: ' . $this->zoom . ',
					 center: latlng,
					 mapTypeId: google.maps.MapTypeId.ROADMAP,
					 streetViewControl: false,
					 mapTypeControl: false
				  };
				   var map = new google.maps.Map(document.getElementById(\'googleMapCanvas' . $this->bID . '\'), mapOptions);
				   var marker = new google.maps.Marker({
					   position: latlng, 
					   map: map
				   });
			   }catch(e){
			   $("#googleMapCanvas'. $this->bID .'").replaceWith("<p>Unable to display map: "+e.message+"</p>")}
			}
			$(function() {
				var t;
				var startWhenVisible = function (){
					if ($("#googleMapCanvas'. $this->bID .'").is(":visible")){
						window.clearInterval(t);
						googleMapInit' . $this->bID . '();
						return true;
					} 
					return false;
				};
				if (!startWhenVisible()){
					t = window.setInterval(function(){startWhenVisible();},100);      
				}
			});            
			</script>');				
		}
	}
	
	public function save($data) { 
		$args['title'] = isset($data['title']) ? trim($data['title']) : '';
		$args['location'] = isset($data['location']) ? trim($data['location']) : '';
		$args['zoom'] = (intval($data['zoom'])>=0 && intval($data['zoom'])<=21) ? intval($data['zoom']) : 14;		
        $args['latitude'] = is_numeric($data['latitude']) ? $data['latitude'] : 0;
        $args['longitude'] = is_numeric($data['longitude']) ? $data['longitude'] : 0;
        $args['width'] = $data['width'];
        $args['height'] = $data['height'];
		parent::save($args);
	}

}
