<?php
/**
 * Fuel is a fast, lightweight, community driven PHP 5.4+ framework.
 *
 * @package    Fuel
 * @version    1.8.2
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2019 Fuel Development Team
 * @link       https://fuelphp.com
 */

/**
 * The Welcome Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 *
 * @package  app
 * @extends  Controller
 */
class Controller_Original extends Controller
{
	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	// public function action_yap($word)
	// {
	// 	echo $word;
	// }

	/**
	 * バリデーション
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_form()
	{
		if(Input::post()){
			$val = Validation::forge();
			$val->add_field('name', '氏名', 'required');
			$val->add_field('tel_no', '電話番号', 'required');
			$val->add_field('mail', 'メールアドレス', 'required');
			$val->add_field('upload_file', 'ファイルアップロード', 'required');
			if($val->run()){
				echo '成功！';
				exit;
			}
			else{
				foreach($val->error() as $key => $value){
					echo $value->get_message();
					echo '<br>';
				}
				exit;
			}
		}
		return View::forge('form');
	}

	/**
	 * ファイルアップロード
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_upload()
	{
		if (Input::method() == 'POST') {
			// 初期設定
			$config = array(
				'path' => DOCROOT.DS.'files',
				'randomize' => true,
				'ext_whitelist' => array('csv'),
			);
			// アップロード基本プロセス実行
			Upload::process($config);
			// 検証
			if (Upload::is_valid()) {
				// 設定を元に保存
				Upload::save();
				// // 情報をデータベースに保存する場合
				// $result = Model_Uploads::deliv_add(Upload::get_files());
			}
			//ファイル名取得
			foreach (Upload::get_files() as $file) {
				echo '保存ファイル名：'. $file['saved_as'];
				echo '<br>';
				echo '保存先パス：'. $file['saved_to'];
				echo '<br>';
				$file_name = $file['saved_to'] . $file['saved_as'];
				echo 'フルパス：'. $file_name;
				echo '<br>';
				$data = file_get_contents($file['saved_to'].$file['saved_as']);
				$data = mb_convert_encoding($data, 'UTF-8', 'SJIS');
				// $data = Format::forge($data, 'csv')->to_array();
				$line = explode(',',$data);
				echo '<p>';
				echo $line[0].',';
				echo $line[1].',';
				echo $line[2].',';
				echo $line[3].',';
				echo $line[4].',';
				echo $line[5];
				echo '<p>';
				echo $line[6].',';
				echo $line[7].',';
				echo $line[8].',';
				echo $line[9].',';
				echo $line[10];

				// foreach ($data as $line) {
				// 	$lines = explode(',',$line);
				// 	echo '<p>';
				// 	echo $lines[0];
				// 	echo $lines[1];
				// 	echo $lines[2];	
				// 	echo '</p>';
				// }
			}
			// エラーを処理する
			foreach (Upload::get_errors() as $file)
			{
				// $file はファイル情報の配列
				// $file['errors'] は発生したエラーの内容を含む配列で、
				// 配列の要素は 'error' と 'message' を含む配列
				foreach($file['errors'] as $error){
					//エラー文を変数へ（Eclog＝[$err_msg]イメージ）
					$err_msg = $error['message'];
					var_dump($err_msg);
				}
			}
			return View::forge('upload');
		}
	}
}
