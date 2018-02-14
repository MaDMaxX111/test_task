<?

class Program {

	private $data;
	private $file = 'data.xml';

	public function __construct() {

		ini_set('error_reporting', E_ALL);
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);

		if (!file_exists ($this->file)) {
			file_put_contents($this->file, '<?xml version="1.0"?><messages></messages>');
		}

		$this->data = file_get_contents($this->file);

		if (!empty($_GET['action'])) {
			return $this->{$_GET['action']}();
		}

	}

	public function addcomment() {

		$json = array();

		if ($errors = $this->validateData($_POST)) {
			$json['error'] = $errors;
			$this->sendRequest($json);
			return;
		}

		date_default_timezone_set('europe/moscow');

		$sXML = new SimpleXMLElement($this->data);

		$count = $sXML->count();
		$newchild = $sXML->addChild("message");
		$newchild->addAttribute("id", ++$count);
		$newchild->addAttribute("date", date("d.m.Y H:i:s", time()));

		if (!empty($_POST['id'])) {
			$newchild->addChild("parent-comment", $_POST['id']);
		}

		$newchild->addChild("name", $_POST['name']);
		$newchild->addChild("comment", $_POST['comment']);

		file_put_contents($this->file, $sXML->asXML());

		$this->sendRequest(['success' => 'Ваше сообщение удачно отправлено!']);

	}

	public function replyComment() {
		if (empty($_POST['id'])) {

			$json['error'][] = 'Что то пошло не так';
			$this->sendRequest($json);
			return;
		}

		return $this->addComment();
	}

	public function editComment() {

		$json = array();

		$sXML = new SimpleXMLElement($this->data);

		if ($errors = $this->validateData($_POST)) {
			$json['error'] = $errors;
			$this->sendRequest($json);
			return;
		}

		if (empty($_POST['id']) || !$editElement = $sXML->xpath('/messages/message[@id="'. $_POST['id'] .'"]')) {

			$json['error'][] = 'Что то пошло не так';
			$this->sendRequest($json);
			return;
		}

		$editElement['0']->name = $_POST['name'];
		$editElement['0']->comment = $_POST['comment'];

		file_put_contents($this->file, $sXML->asXML());

		$this->sendRequest(['success' => 'Ваше сообщение удачно отправлено!']);

	}

	public function getComments() {

		$sXML = new SimpleXMLElement($this->data);

		$comments = array();

		$array = $multi_array = json_decode( json_encode($sXML) , 1);

		if (!empty($array['message'])) {
			foreach ($array['message'] as $message) {
				$parent = (!empty($message['parent-comment'])) ? $message['parent-comment'] : 0;
				$comments[$parent][$message['@attributes']['id']] = [
					'id'      => $message['@attributes']['id'],
					'date'    => $message['@attributes']['date'],
					'parent'  => $parent,
					'name'    => $message['name'],
					'comment' => $message['comment'],
				];
			}

			header("Content-type: text/html; charset=utf-8");

			echo $this->renderComments($comments);
		} else {
			return false;
		}

	}

	private function sendRequest($json) {

		header("Content-type: application/json; charset=utf-8");
		echo json_encode($json);

	}

	private function validateData($data) {

		$error = array();

		$deltaTime = time() - filemtime($this->file) - 10;
		if ($deltaTime < 0) {
			$error['time'] = 'Повторите запрос через ' . abs($deltaTime) . ' сек';
		}

		foreach ($data as $key => $value) {

			switch ($key) {
				case 'name':
				case 'comment':
					if (mb_strlen($value, 'UTF-8') < 3) {
						$error[$key] = 'Поле ' .$key . ' должно быть не менее 3 символов';
 					}
					break;

				default:
					break;
			}
		}

		return $error;
	}

	private function renderComments($comments, $parent_id = 0, $level = 0) {

		$html = '';

		foreach ($comments[$parent_id] as $comment) {

			$html .= '<div class="comments" data-message-id="'. $comment['id'] .'">
						<span class="time">' . $comment['date'] . '</span>
						<span class="name">' . $comment['name'] . '</span>
						<span class="comment">' . $comment['comment'] . '</span>';

			if ($level < 2) {
				$html .= '<a href="javascript:void(0);"class="reply">Ответить</a>';
			}

			if (!empty($comments[$comment['id']]) && is_array($comments[$comment['id']])) {
				$html .= $this->renderComments($comments, $comment['id'], ($level+1));
			}

			$html .= '</div>';
		}

		return $html;
	}


}

new Program();