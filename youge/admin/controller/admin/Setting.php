<?php
namespace app\admin\controller;
use app\admin\controller\Common;
use think\Validate;


class Setting extends Common
{
	public function index()
	{
		$setting = model('pay')->getSetting($this->applet_id);
		if (request()->method() == 'GET') {
			$this->assign('setting', $setting);
			return $this->fetch();
		} else {
			$data = request()->param();
			$validate = new Validate([
				'appid' => 'require',
				'appsecret' => 'require',
				'mchid' => 'require',
				'mchkey' => 'require',
			]);
			if (!$validate->check($data)) {
				return [
					'status' => 'error',
					'msg' => $validate->getError()
				];
			}
			$cert_pem = request()->file('cert_pem');
			$key_pem = request()->file('key_pem');
			//if (!$cert_pem) {
			//	return [
					//'status' => 'error',
			//		'msg' => '请上传证书cert_pem'
			//	];
			//}
			//if (!$key_pem) {
			//	return [
			//		//'status' => 'error',
			//		'msg' => '请上传证书key_pem'
			////	];
			//}
			$cert_info = $cert_pem->move(ROOT_PATH);
			$key_info = $key_pem->move(ROOT_PATH);
			$setting->appid = $data['appid'];
			$setting->appsecret = $data['appsecret'];
			$setting->mchid = $data['mchid'];
			$setting->mchkey = $data['mchkey'];
			$setting->cert_pem = str_replace('\\', '/', $cert_info->getSaveName());
			$setting->key_pem = str_replace('\\', '/', $key_info->getSaveName());
			$setting->save();
			return [
				'status' => 'success',
				'msg' => '保存成功'
			];
		}
	}
}