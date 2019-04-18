<?php
/**
 * Created by PhpStorm.
 * User: gongyuanyi
 * Date: 2019/4/2
 * Time: 上午9:44
 */

namespace Wechat\OpenSDK;

use think\facade\Cache;
use think\facade\Log;

/**
 * 微信开放平台SDK
 * @author  gongyuanyi
 * Class WechatMp
 */
class WechatOpen extends WechatOrigin
{
    private $appid;
    private $appsecret;
    public $access_token;
    public $authorizer_access_token;

    public $errCode = 0;
    public $errMsg = 'ok';

    const API_URL_PREFIX = 'https://api.weixin.qq.com/cgi-bin';
    const WXA_URL_PREFIX = 'https://api.weixin.qq.com/wxa';
    //服务器地址
    const SET_DNS_URL = '/modify_domain?'; //设置小程序服务器域名
    const SET_WEB_VIEM_DOMAIN_URL = '/setwebviewdomain?'; //设置小程序业务域名
    //小程序基本信息
    const GET_ACCOUNT_BASIC_INFO = '/account/getaccountbasicinfo?'; //获取帐号基本信息
    const SET_NICKNAME = '/setnickname?'; //小程序名称设置及改名
    const GET_WXA_QUERYNICKNAME = '/api_wxa_querynickname?'; //小程序改名审核状态查询
    const CHECK_WX_VERIFYNICKNAME = '/wxverify/checkwxverifynickname?'; //微信认证名称检测
    const MODIFY_HEADIMAGE = '/account/modifyheadimage?'; //修改头像
    const MODIFY_SIGNATURE = '/account/modifysignature?'; //修改功能介绍
    const COMPONENT_REBIND_ADMIN_URL = 'https://mp.weixin.qq.com/wxopen/componentrebindadmin?'; // 换绑小程序管理员 授权注册url
    const COMPONENT_REBIND_ADMIN = '/account/componentrebindadmin?'; // 完成管理员换绑
    const GET_ALL_CATEGORIES = '/wxopen/getallcategories?'; // 获取账号可以设置的所有类目
    const ADD_CATEGORY = '/wxopen/addcategory?'; // 添加类目
    const DELETE_CATEGORY = '/wxopen/deletecategory?'; // 删除类目
    const GET_CATEGORY = '/wxopen/getcategory?'; // 获取账号已经设置的所有类目
    const MODIFY_CATEGORY = '/wxopen/modifycategory?'; // 修改类目
    //基础信息
    const CHANGE_WXA_SEARCH_STATUS = '/changewxasearchstatus?'; // 设置小程序隐私设置（是否可被搜索）
    const GET_WXA_SEARCH_STATUS = '/ getwxasearchstatus?'; // 查询小程序当前隐私设置（是否可被搜索）
    const GET_SHOW_WXA_ITEM = '/getshowwxaitem?'; //  小程序扫码公众号关注组件 获取展示的公众号信息
    const UPDATE_SHOW_WXA_ITEM = '/updateshowwxaitem?'; // 小程序扫码公众号关注组件  设置展示的公众号
    const GET_WXA_MP_LINK_FOR_SHOW = '/getwxamplinkforshow?'; // 小程序扫码公众号关注组件  获取可以用来设置的公众号列表
    //成员管理
    const BIND_TESTER_URL = '/bind_tester?'; //绑定微信用户为小程序体验者
    const UNBIND_TESTER_URL = '/unbind_tester?'; //解除绑定小程序的体验者
    const MEMBER_AUTH = '/memberauth?'; //获取体验者列表
    //代码管理
    const UPLOAD_URL = '/commit?'; //上传小程序
    const QRCODE_URL = '/get_qrcode?'; //小程序体验二维码
    const CATEGORY_URL = '/get_category?'; //小程序账号类目
    const GET_PAGE_URL = '/get_page?'; //小程序的页面配置
    const CHECK_URL = '/submit_audit?'; //小程序提交审核
    const AUDITSTATUS_URL = '/get_auditstatus?'; //查询某个指定版本的审核状态
    const LATEST_AUDITSTATUS_URL = '/get_latest_auditstatus?'; //查询最新一次提交的审核状态
    const UNDOCODEAUDIT_URL = '/undocodeaudit?'; //小程序审核撤回
    const RELEASE_URL = '/release?'; //发布小程序
    const WXACODE_URL = '/getwxacode?'; //小程序码
    const UNBIND_URL = '/open/unbind?'; //解绑公众号／小程序
    const WXACODE_UNLIMIT_URL = '/getwxacodeunlimit?'; //批量小程序码(适用于需要的码数量极多的业务场景)
    const CHANGE_VISIT_STATUS = '/change_visitstatus?'; //修改小程序线上代码的可见状态
    const REVERT_CODE_RELEASE = '/revertcoderelease?'; //小程序版本回退
    const GET_WEAPP_SUPPORT_VERSION = '/wxopen/getweappsupportversion?'; //查询当前设置的最低基础库版本及各版本用户占比
    const SET_WEAPP_SUPPORT_VERSION = '/wxopen/setweappsupportversion?'; //设置最低基础库版本
    const QRCODE_JUMP_ADD = '/wxopen/qrcodejumpadd?'; //增加或修改二维码规则
    const QRCODE_JUMP_GET = '/wxopen/qrcodejumpget?'; //获取已设置的二维码规则
    const QRCODE_JUMP_DOWN_LOAD = '/wxopen/qrcodejumpdownload?'; //获取校验文件名称及内容
    const QRCODE_JUMP_DELETE = '/wxopen/qrcodejumpdelete?'; //删除已设置的二维码规则
    const QRCODE_JUMP_PUBLISH = '/wxopen/qrcodejumppublish?'; //发布已设置的二维码规则
    const GRAY_RELEASE = '/grayrelease?'; //小程序分阶段发布  分阶段发布接口
    const REVERT_GRAY_RELEASE = '/revertgrayrelease?'; //取消分阶段发布
    const GET_GRAY_RELEASE_PLAN = '/getgrayreleaseplan?'; //查询当前分阶段发布详情
    //小程序模板设置
    const TEMPLATE_LIBRARY_LIST = '/wxopen/template/library/list?'; //获取小程序模板库标题列表
    const GET_TEMPLATE_LIBRARY = '/wxopen/template/library/get?'; //获取模板库某个模板标题下关键词库
    const ADD_TEMPLATE = '/wxopen/template/add?'; //组合模板并添加至帐号下的个人模板库
    const TEMPLATE_LIST = '/wxopen/template/list?'; //获取帐号下已存在的模板列表
    const DEL_TEMPLATE = '/wxopen/template/del?'; //删除帐号下的某个模板
    //用户管理
    const GET_PAID_UNIONID = '/getpaidunionid?'; //支付后获取用户Unionid接口
    //微信开放平台帐号管理
    const OPEN_CEATE = '/open/create?'; //  创建 开放平台帐号并绑定公众号/小程序
    const OPEN_BIND = '/open/bind?'; // 将公众号/小程序绑定到开放平台帐号下
    const OPEN_UNBIND = '/open/unbind?'; // 将公众号/小程序从开放平台帐号下解绑
    const OPEN_GET = '/open/get?'; // 获取公众号/小程序所绑定的开放平台帐号
    //小程序插件管理权限集
    const PLUGIN = 'https://api.weixin.qq.com/wxa/plugin?'; // 申请使用插件

    /**
     * 构造函数
     * @param $type string 用户在小程序登录后获取的会话密钥
     */
    public function __construct($type = 'component')
    {
        $config = Config('wechat.' . $type);
        parent::__construct($config);
    }

    /**
     * 获取错误代码
     * @return int
     */
    public function getErrorCode()
    {
        return $this->errCode;
    }

    /**
     * 获取错误信息
     * @return string
     */
    public function getErrorMsg()
    {
        return $this->errMsg;
    }

    /**
     * GET 请求
     * @param string $url
     */
    public function http_get($url)
    {
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }

    /**
     * POST 请求
     * @param string $url
     * @param array $param
     * @param boolean $post_file 是否文件上传
     * @return string content
     */
    public function http_post($url, $param, $post_file = false)
    {
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        if (is_string($param) || $post_file) {
            $strPOST = $param;
        } else {
            $aPOST = array();
            foreach ($param as $key => $val) {
                $aPOST[] = $key . "=" . urlencode($val);
            }
            $strPOST = join("&", $aPOST);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POST, true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }

    /**
     * 设置小程序服务器域名
     * @param string $action add添加, delete删除, set覆盖, get获取。当参数是get时不需要填四个域名字段
     * @param array $data 服务器域名数组
     * @return bool|string
     */
    public function setDomain($action = 'set', $data)
    {
        if (!$this->authorizer_access_token) return false;
        $params = array(
            'action' => $action,
            'requestdomain' => $data['request_domain'],
            'wsrequestdomain' => $data['wsrequest_domain'],
            'uploaddomain' => $data['upload_domain'],
            'downloaddomain' => $data['download_domain'],
        );
        $result = $this->http_post(self::WXA_URL_PREFIX . self::SET_DNS_URL . 'access_token=' . $this->authorizer_access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case 85015:
                        throw new \Exception('该账号不是小程序账号!');
                        break;
                    case 85016:
                        throw new \Exception('域名数量超过限制!');
                        break;
                    case 85017:
                        throw new \Exception('没有新增域名，请确认小程序已经添加了域名或该域名是否没有在第三方平台添加!');
                        break;
                    case 85018:
                        throw new \Exception('域名没有在第三方平台设置!');
                        break;
                    default:
                        throw new \Exception('小程序服务器域名设置出错!');
                        break;
                }
            }

            return true;
        }
        return false;
    }

    /**
     * 设置小程序业务域名
     * @param string $action add添加, delete删除, set覆盖, get获取。
     * @param string $domain 业务域名
     * @return bool|string
     */
    public function setWebViewDomain($action = 'set', $domain)
    {
        if (!$this->authorizer_access_token) return false;
        $params = array(
            'action' => $action,
            'webviewdomain' => $domain,
        );
        $result = $this->http_post(self::WXA_URL_PREFIX . self::SET_WEB_VIEM_DOMAIN_URL . 'access_token=' . $this->authorizer_access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case 89019:
                        throw new \Exception('业务域名无更改，无需重复设置!');
                        break;
                    case 89020:
                        throw new \Exception('尚未设置小程序业务域名，请先在第三方平台中设置小程序业务域名后在调用本接口!');
                        break;
                    case 89021:
                        throw new \Exception('请求保存的域名不是第三方平台中已设置的小程序业务域名或子域名!');
                        break;
                    case 89029:
                        throw new \Exception('业务域名数量超过限制!');
                        break;
                    case 89231:
                        throw new \Exception('个人小程序不支持调用setwebviewdomain 接口!');
                        break;
                    default:
                        throw new \Exception('小程序业务域名设置出错!');
                        break;
                }
            }

            return $result;
        }
        return false;
    }

    /**
     * 获取帐号基本信息
     * @return bool|string
     */
    public function getAccountBasicInfo()
    {
        if (!$this->authorizer_access_token) return false;
        $result = $this->http_get(self::API_URL_PREFIX . self::GET_ACCOUNT_BASIC_INFO . 'access_token=' . $this->authorizer_access_token);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
            }

            return $result;
        }
        return false;
    }

    /**
     * 小程序名称设置及改名
     * @param string $nickName 昵称
     * @param string $idCard 身份证照片–临时素材mediaid
     * @param string $license 组织机构代码证或营业执照–临时素材mediaid
     * @param string $namingOtherStuff1 其他证明材料---临时素材 mediaid
     * @param string $namingOtherStuff2 其他证明材料---临时素材 mediaid
     * @return bool|string
     */
    public function setNickname($nickName, $idCard, $license, $namingOtherStuff1 = '', $namingOtherStuff2 = '')
    {
        if (!$this->authorizer_access_token) return false;
        $params = array(
            'nick_name' => $nickName,
            'id_card' => $idCard,
            'license' => $license,
            'naming_other_stuff_1' => $namingOtherStuff1,
            'naming_other_stuff_2' => $namingOtherStuff2,
        );
        $result = $this->http_post(self::WXA_URL_PREFIX . self::SET_NICKNAME . 'access_token=' . $this->authorizer_access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case 91001:
                        throw new \Exception('不是公众号快速创建的小程序!');
                        break;
                    case 91002:
                        throw new \Exception('小程序发布后不可改名!');
                        break;
                    case 91003:
                        throw new \Exception('改名状态不合法!');
                        break;
                    case 91004:
                        throw new \Exception('昵称不合法!');
                        break;
                    case 91005:
                        throw new \Exception('昵称15天主体保护!');
                        break;
                    case 91006:
                        throw new \Exception('昵称命中微信号!');
                        break;
                    case 91007:
                        throw new \Exception('昵称已被占用!');
                        break;
                    case 91008:
                        throw new \Exception('昵称命中7天侵权保护期!');
                        break;
                    case 91009:
                        throw new \Exception('需要提交材料!');
                        break;
                    case 91010:
                        throw new \Exception('其他错误!');
                        break;
                    case 91011:
                        throw new \Exception('查不到昵称修改审核单信息!');
                        break;
                    case 91012:
                        throw new \Exception('其他错误!');
                        break;
                    case 91013:
                        throw new \Exception('占用名字过多!');
                        break;
                    case 91014:
                        throw new \Exception('+号规则 同一类型关联名主体不一致!');
                        break;
                    case 91015:
                        throw new \Exception('原始名不同类型主体不一致!');
                        break;
                    case 91016:
                        throw new \Exception('名称占用者≥2!');
                        break;
                    case 91017:
                        throw new \Exception('+号规则 不同类型关联名主体不一致!');
                        break;
                }
            }

            return $result;
        }
        return false;
    }

    /**
     * 小程序改名审核状态查询
     * @param string $auditId 审核单id
     * @return bool|string
     */
    public function getWxaQuerynickname($auditId)
    {
        if (!$this->authorizer_access_token) return false;
        $params = array(
            'audit_id' => $auditId,
        );

        $result = $this->http_post(self::WXA_URL_PREFIX . self::GET_WXA_QUERYNICKNAME . 'access_token=' . $this->authorizer_access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case 91011:
                        throw new \Exception('查不到昵称修改审核单信息!');
                        break;
                    case 91012:
                        throw new \Exception('其他错误!');
                        break;
                }
            }

            return $result;
        }
        return false;
    }

    /**
     * 微信认证名称检测
     * @param string $nickName 名称（昵称）
     * @return bool|string
     */
    public function checkWxVerifynickname($nickName)
    {
        if (!$this->authorizer_access_token) return false;
        $params = array(
            'nick_name' => $nickName,
        );

        $result = $this->http_post(self::API_URL_PREFIX . self::CHECK_WX_VERIFYNICKNAME . 'access_token=' . $this->authorizer_access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case 53010:
                        throw new \Exception('名称格式不合法!');
                        break;
                    case 53011:
                        throw new \Exception('名称检测命中频率限制!');
                        break;
                    case 53012:
                        throw new \Exception('禁止使用该名称!');
                        break;
                    case 53013:
                        throw new \Exception('公众号：名称与已有公众号名称重复;小程序：该名称与已有小程序名称重复!');
                        break;
                    case 53014:
                        throw new \Exception('公众号：公众号已有{名称A+}时，需与该帐号相同主体才可申请{名称A};小程序：小程序已有{名称A+}时，需与该帐号相同主体才可申请{名称A}!');
                        break;
                    case 53015:
                        throw new \Exception('公众号：公众号已有{名称A+}时，需与该帐号相同主体才可申请{名称A};小程序：小程序已有{名称A+}时，需与该帐号相同主体才可申请{名称A}!');
                        break;
                    case 53016:
                        throw new \Exception('公众号：该名称与已有多个小程序名称重复，暂不支持申请;小程序：该名称与已有多个公众号名称重复，暂不支持申请!');
                        break;
                    case 53017:
                        throw new \Exception('公众号：小程序已有{名称A+}时，需与该帐号相同主体才可申请{名称A};小程序：公众号已有{名称A+}时，需与该帐号相同主体才可申请{名称A}!');
                        break;
                    case 53018:
                        throw new \Exception('名称命中微信号!');
                        break;
                    case 53019:
                        throw new \Exception('名称在保护期内!');
                        break;
                }
            }

            return $result;
        }
        return false;
    }

    /**
     * 修改头像
     * @param media_id $headImgMediaId 头像素材media_id
     * @param float $x1 裁剪框左上角x坐标（取值范围：[0, 1]）
     * @param float $y1 裁剪框左上角y坐标（取值范围：[0, 1]）
     * @param float $x2 裁剪框右下角x坐标（取值范围：[0, 1]）
     * @param float $y2 裁剪框右下角y坐标（取值范围：[0, 1]）
     * @return bool|string
     */
    public function modifyHeadimage($headImgMediaId, $x1, $y1, $x2, $y2)
    {
        if (!$this->authorizer_access_token) return false;
        $params = array(
            'head_img_media_id' => $headImgMediaId,
            'x1' => $x1,
            'y1' => $y1,
            'x2' => $x2,
            'y2' => $y2,
        );

        $result = $this->http_post(self::API_URL_PREFIX . self::MODIFY_HEADIMAGE . 'access_token=' . $this->authorizer_access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case 40097:
                        throw new \Exception('参数错误!');
                        break;
                    case 41006:
                        throw new \Exception('media_id不能为空!');
                        break;
                    case 40007:
                        throw new \Exception('非法的media_id!');
                        break;
                    case 46001:
                        throw new \Exception('media_id不存在!');
                        break;
                    case 40009:
                        throw new \Exception('图片尺寸太大!');
                        break;
                    case 53202:
                        throw new \Exception('本月头像修改次数已用完!');
                        break;
                }
            }

            return $result;
        }
        return false;
    }

    /**
     * 修改功能介绍
     * @param string $signature 功能介绍（简介）
     * @return bool|string
     */
    public function modifySignature($signature)
    {
        if (!$this->authorizer_access_token) return false;
        $params = array(
            'signature' => $signature,
        );

        $result = $this->http_post(self::API_URL_PREFIX . self::MODIFY_SIGNATURE . 'access_token=' . $this->authorizer_access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case 40097:
                        throw new \Exception('参数错误!');
                        break;
                    case 53200:
                        throw new \Exception('本月功能介绍修改次数已用完!');
                        break;
                    case 53201:
                        throw new \Exception('功能介绍内容命中黑名单关键字!');
                        break;
                }
            }

            return $result;
        }
        return false;
    }

    /**
     * 换绑小程序管理员接口   第一步：从第三方平台跳转至微信公众平台授权注册页面
     * @param $authorizer_appid  授权公众号／小程序的appid
     * @param $redirect_uri  新管理员信息填写完成点击提交后，将跳转到该地址(注：1.链接需 urlencode 2.Host需和第三方平台在微信开放平台上面填写的登录授权的发起页域名一致)
     * @return bool|string
     */
    public function componentRebindAdminUrl($authorizer_appid, $redirect_uri)
    {
        $url = self::COMPONENT_REBIND_ADMIN_URL . 'appid=' . $authorizer_appid . '&component_appid=' . $this->appid .
            '&redirect_uri=' . urlencode($redirect_uri);
        return $url;
    }

    /**
     * 换绑小程序管理员接口   第三步：完成管理员换绑
     * @param $taskid  换绑管理员任务序列号(公众平台最终点击提交回跳到第三方平台时携带)
     * @return bool|string
     */
    public function componentRebindAdmin($taskid)
    {
        if (!$this->authorizer_access_token) return false;
        $params = array(
            'taskid' => $taskid,
        );

        $result = $this->http_post(self::API_URL_PREFIX . self::COMPONENT_REBIND_ADMIN . 'access_token=' . $this->authorizer_access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case 85060:
                        throw new \Exception('无效的taskid!');
                        break;
                    case 85027:
                        throw new \Exception('身份证绑定管理员名额达到上限!');
                        break;
                    case 85061:
                        throw new \Exception('手机号绑定管理员名额达到上限!');
                        break;
                    case 85026:
                        throw new \Exception('微信号绑定管理员名额达到上限!');
                        break;
                    case 85063:
                        throw new \Exception('身份证黑名单!');
                        break;
                    case 85062:
                        throw new \Exception('手机号黑名单!');
                        break;
                }
            }

            return $result;
        }
        return false;
    }

    /**
     * 获取账号可以设置的所有类目
     * @return bool|string
     */
    public function getAllCategories()
    {
        if (!$this->authorizer_access_token) return false;
        $result = $this->http_get(self::API_URL_PREFIX . self::GET_ALL_CATEGORIES . 'access_token=' . $this->authorizer_access_token);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
            }

            return $result;
        }
        return false;
    }

    /**
     * 添加类目
     * @param $categories  类目对象
     * @return bool|string
     */
    public function addCategory($categories)
    {
        if (!$this->authorizer_access_token) return false;
        $params = array(
            '$categories' => $categories,
        );

        $result = $this->http_post(self::API_URL_PREFIX . self::ADD_CATEGORY . 'access_token=' . $this->authorizer_access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
            }

            return $result;
        }
        return false;
    }

    /**
     * 删除类目
     * @param $categories  类目对象
     * @return bool|string
     */
    public function deleteCategory($categories)
    {
        if (!$this->authorizer_access_token) return false;
        $result = $this->http_post(self::API_URL_PREFIX . self::DELETE_CATEGORY . 'access_token=' . $this->authorizer_access_token, $categories);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
            }

            return $result;
        }
        return false;
    }

    /**
     * 获取账号已经设置的所有类目
     * @return bool|string
     */
    public function getSetCategory()
    {
        if (!$this->authorizer_access_token) return false;
        $result = $this->http_get(self::API_URL_PREFIX . self::GET_CATEGORY . 'access_token=' . $this->authorizer_access_token);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
            }

            return $result;
        }
        return false;
    }

    /**
     * 修改类目
     * @param $categories  类目对象
     * @return bool|string
     */
    public function modifyCategory($categories)
    {
        if (!$this->authorizer_access_token) return false;
        $result = $this->http_post(self::API_URL_PREFIX . self::MODIFY_CATEGORY . 'access_token=' . $this->authorizer_access_token, $categories);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case 53300:
                        throw new \Exception('超出每月次数限制!');
                        break;
                    case 53301:
                        throw new \Exception('超出可配置类目总数限制!');
                        break;
                    case 53302:
                        throw new \Exception('当前账号主体类型不允许设置此种类目!');
                        break;
                    case 53303:
                        throw new \Exception('提交的参数不合法!');
                        break;
                    case 53304:
                        throw new \Exception('与已有类目重复!');
                        break;
                    case 53305:
                        throw new \Exception('包含未通过IPC校验的类目!');
                        break;
                    case 53306:
                        throw new \Exception('修改类目只允许修改类目资质，不允许修改类目ID!');
                        break;
                    case 53307:
                        throw new \Exception('只有审核失败的类目允许修改!');
                        break;
                    case 53308:
                        throw new \Exception('审核中的类目不允许删除!');
                        break;
                }
            }

            return $result;
        }
        return false;
    }


    /**
     * 设置小程序隐私设置（是否可被搜索）
     * @param $status  搜索状态：1表示不可搜索，0表示可搜索
     * @return bool|string
     */
    public function changeWxaSearchStatus($status)
    {
        if (!$this->access_token) return false;
        $params = array(
            'status' => $status,
        );

        $result = $this->http_post(self::WXA_URL_PREFIX . self::CHANGE_WXA_SEARCH_STATUS . 'access_token=' . $this->access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case -1:
                        throw new \Exception('系统错误!');
                        break;
                    case 85083:
                        throw new \Exception('搜索标记位被封禁，无法修改!');
                        break;
                    case 85084:
                        throw new \Exception('非法的status值，只能填0或者1!');
                        break;
                }
            }

            return $result;
        }
        return false;
    }

    /**
     * 查询小程序当前隐私设置（是否可被搜索）
     * @return bool|string
     */
    public function getWxaSearchStatus()
    {
        if (!$this->access_token) return false;
        $result = $this->http_get(self::WXA_URL_PREFIX . self::GET_WXA_SEARCH_STATUS . 'access_token=' . $this->access_token);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
            }

            return $result;
        }
        return false;
    }

    /**
     * 小程序扫码公众号关注组件  获取展示的公众号信息
     * @return bool|string
     */
    public function getShowWxaItem()
    {
        if (!$this->access_token) return false;
        $result = $this->http_get(self::WXA_URL_PREFIX . self::GET_SHOW_WXA_ITEM . 'access_token=' . $this->access_token);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
            }

            return $result;
        }
        return false;
    }

    /**
     * 小程序扫码公众号关注组件  设置展示的公众号
     * @param int $wxaSubscribeBizFlag 0 关闭，1 开启
     * @param string $appid 如果开启，新的公众号appid
     * @return bool|string
     */
    public function updateShowWxaItem($wxaSubscribeBizFlag, $appid)
    {
        if (!$this->access_token) return false;
        $params = array(
            'wxa_subscribe_biz_flag' => $wxaSubscribeBizFlag,
            'appid' => $appid,
        );
        $result = $this->http_post(self::WXA_URL_PREFIX . self::UPDATE_SHOW_WXA_ITEM . 'access_token=' . $this->access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
            }

            return $result;
        }
        return false;
    }

    /**
     * 小程序扫码公众号关注组件  获取可以用来设置的公众号列表
     * @param int $page 第几页，从0开始
     * @param int $num 每页记录数，最大为20
     * @return bool|string
     */
    public function getWxaMpLinkForShow($page = 0, $num = 20)
    {
        if (!$this->access_token) return false;
        $result = $this->http_get(self::WXA_URL_PREFIX . self::GET_WXA_MP_LINK_FOR_SHOW . 'page=' . $page . '&num=' . $num . '&access_token=' . $this->access_token);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
            }

            return $result;
        }
        return false;
    }

    /**
     * 绑定微信用户为小程序体验者
     * @param   $wechatid  微信号
     * @return bool|string
     */
    public function bindTester($wechatid)
    {
        if (!$this->authorizer_access_token) return false;
        $params = array(
            'wechatid' => $wechatid,
        );

        $result = $this->http_post(self::WXA_URL_PREFIX . self::BIND_TESTER_URL . 'access_token=' . $this->authorizer_access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case -1:
                        throw new \Exception('系统错误!');
                        break;
                    case 85001:
                        throw new \Exception('微信号不存在或微信号设置为不可搜索!');
                        break;
                    case 85002:
                        throw new \Exception('小程序绑定的体验者数量达到上限!');
                        break;
                    case 85003:
                        throw new \Exception('微信号绑定的小程序体验者达到上限!');
                        break;
                    case 85004:
                        throw new \Exception('微信号已经绑定!');
                        break;
                }
            }

            return $json;
        }
        return false;
    }

    /**
     * 解绑微信用户为小程序体验者
     * @param   $wechatid  微信号
     * @return bool|string
     */
    public function unbindTester($wechatid)
    {
        if (!$this->authorizer_access_token) return false;
        $params = array(
            'wechatid' => $wechatid,
        );

        $result = $this->http_post(self::WXA_URL_PREFIX . self::UNBIND_TESTER_URL . 'access_token=' . $this->authorizer_access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }

            return $json;
        }
        return false;
    }

    /**
     * 获取体验者列表
     * @return bool|string
     */
    public function memberAuth()
    {
        if (!$this->authorizer_access_token) return false;
        $params = array(
            'action' => 'get_experiencer',
        );
        $result = $this->http_post(self::WXA_URL_PREFIX . self::MEMBER_AUTH . 'access_token=' . $this->authorizer_access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
            }

            return $json;
        }
        return false;
    }

    /**
     * 为授权的小程序帐号上传小程序代码
     * @param int $templateId 代码库中的代码模版ID
     * @param string $extJson 第三方自定义的配置
     * @param string $userVersion 代码版本号
     * @param string $userDesc 代码描述
     * @return bool|string
     */
    public function upload($templateId, $extJson, $userVersion, $userDesc)
    {
        if (!$this->authorizer_access_token) return false;
        $params = array(
            'template_id' => $templateId,
            'ext_json' => $extJson,
            'user_version' => $userVersion,
            'user_desc' => $userDesc,
        );
        $result = $this->http_post(self::WXA_URL_PREFIX . self::UPLOAD_URL . 'access_token=' . $this->authorizer_access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case -1:
                        throw new \Exception('系统繁忙!');
                        break;
                    case 85013:
                        throw new \Exception('无效的自定义配置!');
                        break;
                    case 85014:
                        throw new \Exception('无效的模版编号!');
                        break;
                    case 85043:
                        throw new \Exception('模版错误!');
                        break;
                    case 85044:
                        throw new \Exception('代码包超过大小限制!');
                        break;
                    case 85045:
                        throw new \Exception('ext_json有不存在的路径!');
                        break;
                    case 85046:
                        throw new \Exception('tabBar中缺少path!');
                        break;
                    case 85047:
                        throw new \Exception('pages字段为空!');
                        break;
                    case 85048:
                        throw new \Exception('ext_json解析失败!');
                        break;
                    case 80082:
                        throw new \Exception('没有权限使用该插件!');
                        break;
                    case 80067:
                        throw new \Exception('找不到使用的插件!');
                        break;
                    case 80066:
                        throw new \Exception('非法的插件版本!');
                        break;
                    default:
                        throw new \Exception('上传出错!');
                        break;
                }
            }

            return true;
        }
        return false;
    }

    /**
     * 获取小程序体验二维码
     * @param
     * @return bool|string
     */
    public function getQrcode($path)
    {
        if (!$this->authorizer_access_token) return false;
        $result = $this->http_get(self::WXA_URL_PREFIX . self::QRCODE_URL . 'access_token=' . $this->authorizer_access_token);
        if (!$result) {
            return false;
        }
        return file_put_contents($path, $result);
    }

    /**
     * 获取授权小程序帐号已设置的类目
     * @param
     * @return bool|string
     */
    public function getCategory()
    {
        if (!$this->authorizer_access_token) return false;
        $result = $this->http_get(self::WXA_URL_PREFIX . self::CATEGORY_URL . 'access_token=' . $this->authorizer_access_token);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }

            return $json;
        }
        return false;
    }

    /**
     * 获取小程序的第三方提交代码的页面配置
     * @param
     * @return bool|string
     */
    public function getPage()
    {
        if (!$this->authorizer_access_token) return false;
        $result = $this->http_get(self::WXA_URL_PREFIX . self::GET_PAGE_URL . 'access_token=' . $this->authorizer_access_token);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case -1:
                        throw new \Exception('系统繁忙!');
                        break;
                    case 86000:
                        throw new \Exception('不是由第三方代小程序进行调用!');
                        break;
                    case 86001:
                        throw new \Exception('不存在第三方的已经提交的代码!');
                        break;
                }
                return false;
            }

            return $json;
        }
        return false;
    }

    /**
     * 将第三方提交的代码包提交审核
     * @param json $postData 提交审核项列表
     * @return bool|string
     */
    public function submitAudit($postData)
    {
        if (!$this->authorizer_access_token) return false;
        $params = $postData;
        $result = $this->http_post(self::WXA_URL_PREFIX . self::CHECK_URL . 'access_token=' . $this->authorizer_access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case -1:
                        throw new \Exception('系统繁忙!');
                        break;
                    case 86000:
                        throw new \Exception('不是由第三方代小程序进行调用!');
                        break;
                    case 86001:
                        throw new \Exception('不存在第三方的已经提交的代码!');
                        break;
                    case 85006:
                        throw new \Exception('标签格式错误!');
                        break;
                    case 85007:
                        throw new \Exception('页面路径错误!');
                        break;
                    case 85008:
                        throw new \Exception('类目填写错误!');
                        break;
                    case 85009:
                        throw new \Exception('已经有正在审核的版本!');
                        break;
                    case 85010:
                        throw new \Exception('item_list有项目为空!');
                        break;
                    case 85011:
                        throw new \Exception('标题填写错误!');
                        break;
                    case 85023:
                        throw new \Exception('审核列表填写的项目数不在1-5以内!');
                        break;
                    case 85077:
                        throw new \Exception('小程序类目信息失效（类目中含有官方下架的类目，请重新选择类目）!');
                        break;
                    case 86002:
                        throw new \Exception('小程序还未设置昵称、头像、简介。请先设置完后再重新提交。');
                        break;
                    case 85085:
                        throw new \Exception('近7天提交审核的小程序数量过多，请耐心等待审核完毕后再次提交!');
                        break;
                    case 85086:
                        throw new \Exception('提交代码审核之前需提前上传代码');
                        break;
                    default:
                        throw new \Exception('审核出错!');
                        break;
                }
            }

            return $json;
        }
        return false;
    }

    /**
     * 查询某个指定版本的审核状态
     * @param $auditid
     * @return bool|string
     */
    public function getAuditstatus($auditid)
    {
        if (!$this->authorizer_access_token) return false;
        $params = array(
            'auditid' => $auditid,
        );

        $result = $this->http_post(self::WXA_URL_PREFIX . self::AUDITSTATUS_URL . 'access_token=' . $this->authorizer_access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case -1:
                        throw new \Exception('系统繁忙!');
                        break;
                    case 86000:
                        throw new \Exception('不是由第三方代小程序进行调用!');
                        break;
                    case 86001:
                        throw new \Exception('不存在第三方的已经提交的代码!');
                        break;
                    case 85012:
                        throw new \Exception('无效的审核id!');
                        break;
                }
                return false;
            }

            return $json;
        }
        return false;
    }

    /**
     * 查询最新一次提交的审核状态
     * @return bool|mixed
     */
    public function getLatestAuditstatus()
    {
        if (!$this->authorizer_access_token) return false;
        $result = $this->http_post(self::WXA_URL_PREFIX . self::LATEST_AUDITSTATUS_URL . 'access_token=' . $this->authorizer_access_token);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case -1:
                        throw new \Exception('系统繁忙!');
                        break;
                    case 86000:
                        throw new \Exception('不是由第三方代小程序进行调用!');
                        break;
                    case 86001:
                        throw new \Exception('不存在第三方的已经提交的代码!');
                        break;
                    case 85012:
                        throw new \Exception('无效的审核id!');
                        break;
                }
                return false;
            }

            return $json;
        }
        return false;
    }

    /**
     * 小程序审核撤回
     * @param
     * @return bool|string
     */
    public function undoCodeAudit()
    {
        if (!$this->authorizer_access_token) return false;
        $result = $this->http_get(self::WXA_URL_PREFIX . self::UNDOCODEAUDIT_URL . 'access_token=' . $this->authorizer_access_token);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case -1:
                        throw new \Exception('系统繁忙!');
                        break;
                    case 87013:
                        throw new \Exception('撤回次数达到上限（每天一次，每个月10次）');
                        break;
                }
                return false;
            }

            return $json;
        }
        return false;
    }

    /**
     * 发布已通过审核的小程序
     * @param
     * @return bool|string
     */
    public function release()
    {
        if (!$this->authorizer_access_token) return false;
        $params = array();
        $result = $this->http_post(self::WXA_URL_PREFIX . self::RELEASE_URL . 'access_token=' . $this->authorizer_access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case -1:
                        throw new \Exception('系统繁忙!');
                        break;
                    case 85019:
                        throw new \Exception('没有审核版本!');
                        break;
                    case 85020:
                        throw new \Exception('审核状态未满足发布!');
                        break;
                    case 85052:
                        throw new \Exception('小程序已发布!');
                        break;
                    default:
                        throw new \Exception('发布出错!');
                        break;
                }
            }

            return $json;
        }
        return false;
    }

    /**
     * 小程序码
     * @param
     * @return bool|string
     */
    public function getWxacode($path, $params = array())
    {
        if (!$this->authorizer_access_token) return false;
        if (empty($params)) {
            $params = array(
                'path' => 'pages/index/index',
            );
        }

        $result = $this->http_post(self::WXA_URL_PREFIX . self::WXACODE_URL . 'access_token=' . $this->authorizer_access_token, self::json_encode($params));

        if (!$result) {
            return false;
        }

        return file_put_contents($path, $result);

    }

    /**
     * 解绑小程序
     * @param
     * @return bool|string
     */
    public function unbind($appid)
    {
        if (!$this->authorizer_access_token) return false;
        $params = array(
            'appid' => $appid,
            'open_appid' => $this->appid,
        );

        $result = $this->http_post(self::API_URL_PREFIX . self::UNBIND_URL . 'access_token=' . $this->authorizer_access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case -1:
                        throw new \Exception('系统繁忙!');
                        break;
                    case 40013:
                        throw new \Exception('appid无效!');
                        break;
                    case 89001:
                        throw new \Exception('not same contractor，Authorizer与开放平台帐号主体不相同!');
                        break;
                    case 89002:
                        throw new \Exception('该公众号/小程序未绑定微信开放平台帐号!');
                        break;
                    case 89003:
                        throw new \Exception('该开放平台帐号并非通过api创建，不允许操作!');
                        break;
                    default:
                        throw new \Exception('解绑出错!');
                        break;
                }
            }

            return $json;
        }
        return false;
    }

    /**
     * 批量小程序码(适用于需要的码数量极多的业务场景)
     * 可传scene场景值
     * @param
     * @return bool|string
     */
    public function getUnlimitWxacode($path, $params = array())
    {
        if (!$this->authorizer_access_token) return false;
        if (empty($params)) {
            $params = array(
                'path' => 'pages/index/index',
            );
        }
        $result = $this->http_post(self::WXA_URL_PREFIX . self::WXACODE_UNLIMIT_URL . 'access_token=' . $this->authorizer_access_token, self::json_encode($params));
        if (!$result) {
            return false;
        }
        return file_put_contents($path, $result);
    }

    /**
     * 修改小程序线上代码的可见状态
     * @param  string $action 设置可访问状态，发布后默认可访问，close为不可见，open为可见
     * @return bool|string
     */
    public function changeVisitStatus($action = 'open')
    {
        if (!$this->authorizer_access_token) return false;
        $params = array(
            'action' => $action,
        );
        $result = $this->http_post(self::WXA_URL_PREFIX . self::CHANGE_VISIT_STATUS . 'access_token=' . $this->authorizer_access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case -1:
                        throw new \Exception('系统繁忙!');
                        break;
                    case 85021:
                        throw new \Exception('状态不可变!');
                        break;
                    case 85022:
                        throw new \Exception('action非法!');
                        break;
                }
            }

            return $json;
        }
        return false;
    }

    /**
     * 小程序版本回退
     * @return bool|string
     */
    public function revertCodeRelease()
    {
        if (!$this->authorizer_access_token) return false;
        $result = $this->http_get(self::WXA_URL_PREFIX . self::REVERT_CODE_RELEASE . 'access_token=' . $this->authorizer_access_token);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case -1:
                        throw new \Exception('系统繁忙!');
                        break;
                    case 87011:
                        throw new \Exception('现网已经在灰度发布，不能进行版本回退!');
                        break;
                    case 87012:
                        throw new \Exception('该版本不能回退，可能的原因：1:无上一个线上版用于回退 2:此版本为已回退版本，不能回退 3:此版本为回退功能上线之前的版本，不能回退!');
                        break;
                }
            }
            return $json;
        }
        return false;
    }

    /**
     * 查询当前设置的最低基础库版本及各版本用户占比
     * @return bool|string
     */
    public function getWeappSupportVersion()
    {
        if (!$this->authorizer_access_token) return false;
        $result = $this->http_post(self::API_URL_PREFIX . self::GET_WEAPP_SUPPORT_VERSION . 'access_token=' . $this->authorizer_access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
            }
            return $json;
        }
        return false;
    }

    /**
     * 设置最低基础库版本
     * @param  string $version 版本
     * @return bool|string
     */
    public function setWeappSupportVersion($version)
    {
        if (!$this->authorizer_access_token) return false;
        $params = array(
            'version' => $version,
        );
        $result = $this->http_post(self::API_URL_PREFIX . self::SET_WEAPP_SUPPORT_VERSION . 'access_token=' . $this->authorizer_access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case -1:
                        throw new \Exception('系统繁忙!');
                        break;
                    case 85015:
                        throw new \Exception('版本输入错误!');
                        break;
                }
            }
            return $json;
        }
        return false;
    }

    /**
     *  设置小程序“扫普通链接二维码打开小程序”  增加或修改二维码规则
     * @param  string $prefix 二维码规则
     * @param  int $permitSubRule 是否独占符合二维码前缀匹配规则的所有子规1为不占用，2为占用
     * @param  string $path 小程序功能页面
     * @param  int $openVersion 测试范围：1为开发版（配置只对开发者生效）2为体验版（配置对管理员、体验者生效）3为线上版本（配置对管理员、开发者和体验者生效）
     * @param  array $debugUrl 测试链接（选填）可填写不多于5个用于测试的二维码完整链接，此链接必须符合已填写的二维码规则。
     * @param  int $isEdit 编辑标志位，0表示新增二维码规则，1表示修改已有二维码规则
     * @return bool|string
     */
    public function qrcodeJumpAdd($prefix, $permitSubRule = 1, $path, $openVersion = 1, $debugUrl = '', $isEdit = 0)
    {
        if (!$this->authorizer_access_token) return false;
        $params = array(
            'prefix' => $prefix,
            'permit_sub_rule' => $permitSubRule,
            'path' => $path,
            'open_version' => $openVersion,
            'debug_url' => $debugUrl,
            'is_edit' => $isEdit,
        );
        $result = $this->http_post(self::API_URL_PREFIX . self::QRCODE_JUMP_ADD . 'access_token=' . $this->authorizer_access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
            }
            return $json;
        }
        return false;
    }

    /**
     *  设置小程序“扫普通链接二维码打开小程序”  获取已设置的二维码规则
     * @return bool|string
     */
    public function qrcodeJumpGet()
    {
        if (!$this->authorizer_access_token) return false;
        $result = $this->http_post(self::API_URL_PREFIX . self::QRCODE_JUMP_GET . 'access_token=' . $this->authorizer_access_token);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
            }
            return $json;
        }
        return false;
    }

    /**
     *  设置小程序“扫普通链接二维码打开小程序”  获取校验文件名称及内容
     * @return bool|string
     */
    public function qrcodeJumpDownLoad()
    {
        if (!$this->authorizer_access_token) return false;
        $result = $this->http_post(self::API_URL_PREFIX . self::QRCODE_JUMP_DOWN_LOAD . 'access_token=' . $this->authorizer_access_token);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
            }
            return $json;
        }
        return false;
    }

    /**
     *  设置小程序“扫普通链接二维码打开小程序”  删除已设置的二维码规则
     * @param  string $prefix 二维码规则
     * @return bool|string
     */
    public function qrcodeJumpDelete($prefix)
    {
        if (!$this->authorizer_access_token) return false;
        $params = array(
            'prefix' => $prefix,
        );
        $result = $this->http_post(self::API_URL_PREFIX . self::QRCODE_JUMP_DELETE . 'access_token=' . $this->authorizer_access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
            }
            return $json;
        }
        return false;
    }

    /**
     *  设置小程序“扫普通链接二维码打开小程序”  发布已设置的二维码规则
     * @param  string $prefix 二维码规则
     * @return bool|string
     */
    public function qrcodeJumpPublish($prefix)
    {
        if (!$this->authorizer_access_token) return false;
        $params = array(
            'prefix' => $prefix,
        );
        $result = $this->http_post(self::API_URL_PREFIX . self::QRCODE_JUMP_PUBLISH . 'access_token=' . $this->authorizer_access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case -1:
                        throw new \Exception('系统繁忙!');
                        break;
                    case 85066:
                        throw new \Exception('链接错误!');
                        break;
                    case 85068:
                        throw new \Exception('测试链接不是子链接!');
                        break;
                    case 85069:
                        throw new \Exception('校验文件失败!');
                        break;
                    case 85070:
                        throw new \Exception('链接为黑名单!');
                        break;
                    case 85071:
                        throw new \Exception('已添加该链接，请勿重复添加!');
                        break;
                    case 85072:
                        throw new \Exception('该链接已被占用!');
                        break;
                    case 85073:
                        throw new \Exception('二维码规则已满!');
                        break;
                    case 85074:
                        throw new \Exception('小程序未发布, 小程序必须先发布代码才可以发布二维码跳转规则!');
                        break;
                    case 85075:
                        throw new \Exception('个人类型小程序无法设置二维码规则!');
                        break;
                    case 85076:
                        throw new \Exception('链接没有ICP备案!');
                        break;
                }
            }
            return $json;
        }
        return false;
    }

    /**
     *  小程序分阶段发布  分阶段发布接口
     * @param  string $grayPercentage 灰度的百分比，1到100的整数
     * @return bool|string
     */
    public function grayRelease($grayPercentage)
    {
        if (!$this->authorizer_access_token) return false;
        $params = array(
            'gray_percentage' => $grayPercentage,
        );
        $result = $this->http_post(self::WXA_URL_PREFIX . self::GRAY_RELEASE . 'access_token=' . $this->authorizer_access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case -1:
                        throw new \Exception('系统繁忙!');
                        break;
                    case 86002:
                        throw new \Exception('小程序未初始化完成!');
                        break;
                    case 85079:
                        throw new \Exception('小程序没有线上版本，不能进行灰度!');
                        break;
                    case 85080:
                        throw new \Exception('小程序提交的审核未审核通过!');
                        break;
                    case 85081:
                        throw new \Exception('无效的发布比例!');
                        break;
                    case 85082:
                        throw new \Exception('当前的发布比例需要比之前设置的高!');
                        break;
                }
            }
            return $json;
        }
        return false;
    }

    /**
     *  小程序分阶段发布  取消分阶段发布
     * @return bool|string
     */
    public function revertGrayRelease()
    {
        if (!$this->authorizer_access_token) return false;
        $result = $this->http_get(self::WXA_URL_PREFIX . self::REVERT_GRAY_RELEASE . 'access_token=' . $this->authorizer_access_token);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
            }
            return $json;
        }
        return false;
    }

    /**
     *  小程序分阶段发布  查询当前分阶段发布详情
     * @return bool|string
     */
    public function getGrayReleasePlan()
    {
        if (!$this->authorizer_access_token) return false;
        $result = $this->http_get(self::WXA_URL_PREFIX . self::GET_GRAY_RELEASE_PLAN . 'access_token=' . $this->authorizer_access_token);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
            }
            return $json;
        }
        return false;
    }

    /**
     * 获取小程序模板库标题列表
     * @param  int $offset offset和count用于分页，表示从offset开始，拉取count条记录，offset从0开始，count最大为20。
     * @param  int $count
     * @return bool|string
     */
    public function templateLibraryList($offset = 0, $count = 20)
    {
        if (!$this->access_token) return false;
        $params = array(
            'offset' => $offset,
            'count' => $count,
        );
        $result = $this->http_post(self::API_URL_PREFIX . self::TEMPLATE_LIBRARY_LIST . 'access_token=' . $this->access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
            }
            return $json;
        }
        return false;
    }

    /**
     * 获取模板库某个模板标题下关键词库
     * @param  int $id 模板标题id
     * @return bool|string
     */
    public function getTemplateLibrary($id)
    {
        if (!$this->access_token) return false;
        $params = array(
            'id' => $id,
        );
        $result = $this->http_post(self::API_URL_PREFIX . self::GET_TEMPLATE_LIBRARY . 'access_token=' . $this->access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
            }
            return $json;
        }
        return false;
    }

    /**
     * 组合模板并添加至帐号下的个人模板库
     * @param  int $id 模板标题id
     * @param  arrar $keywordIdList 开发者自行组合好的模板关键词列表，关键词顺序可以自由搭配（例如[3,5,4]或[4,5,3]），最多支持10个关键词组合
     * @return bool|string
     */
    public function addTemplate($id, $keywordIdList)
    {
        if (!$this->access_token) return false;
        $params = array(
            'id' => $id,
            'keyword_id_list' => $keywordIdList,
        );
        $result = $this->http_post(self::API_URL_PREFIX . self::ADD_TEMPLATE . 'access_token=' . $this->access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
            }
            return $json;
        }
        return false;
    }

    /**
     * 获取帐号下已存在的模板列表
     * @param  int $offset offset和count用于分页，表示从offset开始，拉取count条记录，offset从0开始，count最大为20。
     * @param  int $count
     * @return bool|string
     */
    public function templateList($offset = 0, $count = 20)
    {
        if (!$this->access_token) return false;
        $params = array(
            'offset' => $offset,
            'count' => $count,
        );
        $result = $this->http_post(self::API_URL_PREFIX . self::TEMPLATE_LIST . 'access_token=' . $this->access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
            }
            return $json;
        }
        return false;
    }

    /**
     * 删除帐号下的某个模板
     * @param  int $templateId 要删除的模板id
     * @return bool|string
     */
    public function delTemplate($templateId)
    {
        if (!$this->access_token) return false;
        $params = array(
            'template_id' => $templateId,
        );
        $result = $this->http_post(self::API_URL_PREFIX . self::DEL_TEMPLATE . 'access_token=' . $this->access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
            }
            return $json;
        }
        return false;
    }

    /**
     * 支付后获取用户Unionid接口
     * @param  string $openid 支付用户唯一标识
     * @param  string $transactionId 微信订单号
     * @param  string $mchId 商户号，和商户订单号配合使用
     * @param  string $outTradeNo 商户订单号，和商户号配合使用
     * @return bool|string
     */
    public function getPaidUnionid($openid, $transactionId = '', $mchId = '', $outTradeNo = '')
    {
        if (!$this->authorizer_access_token) return false;
        if (!$mchId) {
            //微信订单号
            $result = $this->http_get(self::WXA_URL_PREFIX . self::GET_PAID_UNIONID . 'access_token=' . $this->authorizer_access_token . '&openid=' . $openid . '&transaction_id=' . $transactionId);
        } else {
            //商户订单号
            $result = $this->http_get(self::WXA_URL_PREFIX . self::GET_PAID_UNIONID . 'access_token=' . $this->authorizer_access_token . '&openid=' . $openid . '&transaction_id=' . $transactionId . '&mch_id=' . $mchId . '&out_trade_no=' . $outTradeNo);
        }
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case -1:
                        throw new \Exception('系统繁忙!');
                        break;
                    case 40003:
                        throw new \Exception('openid错误!');
                        break;
                    case 89002:
                        throw new \Exception('没有绑定开放平台账号!');
                        break;
                    case 89300:
                        throw new \Exception('订单无效!');
                        break;
                }
            }
            return $json;
        }
        return false;
    }

    /**
     *  创建 开放平台帐号并绑定公众号/小程序
     * @param  string $appid 授权公众号或小程序的 appid
     * @return bool|string
     */
    public function openCeate($appid)
    {
        if (!$this->access_token) return false;
        $params = array(
            'appid' => $appid,
        );
        $result = $this->http_post(self::API_URL_PREFIX . self::OPEN_CEATE . 'access_token=' . $this->access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case -1:
                        throw new \Exception('系统繁忙!');
                        break;
                    case 40013:
                        throw new \Exception('appid 无效!');
                        break;
                    case 89000:
                        throw new \Exception('该公众号 / 小程序 已经绑定了开放平台帐号!');
                        break;
                }
            }
            return $json;
        }
        return false;
    }

    /**
     *  将公众号/小程序绑定到开放平台帐号下
     * @param  string $appid 授权公众号或小程序的 appid
     * @param  string $openAppid 开放平台帐号appid
     * @return bool|string
     */
    public function openBind($appid, $openAppid)
    {
        if (!$this->access_token) return false;
        $params = array(
            'appid' => $appid,
            'open_appid' => $openAppid,
        );
        $result = $this->http_post(self::API_URL_PREFIX . self::OPEN_BIND . 'access_token=' . $this->access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case -1:
                        throw new \Exception('系统繁忙!');
                        break;
                    case 40013:
                        throw new \Exception('appid 无效!');
                        break;
                    case 89000:
                        throw new \Exception('该公众号 / 小程序 已经绑定了开放平台帐号!');
                        break;
                    case 89001:
                        throw new \Exception('uthorizer与开放平台帐号主体不相同!');
                        break;
                    case 89003:
                        throw new \Exception('该开放平台帐号并非通过api创建，不允许操作!');
                        break;
                    case 89004:
                        throw new \Exception('该开放平台帐号所绑定的公众号/小程序已达上限（100个）!');
                        break;
                }
            }
            return $json;
        }
        return false;
    }

    /**
     *  将公众号/小程序从开放平台帐号下解绑
     * @param  string $appid 授权公众号或小程序的 appid
     * @param  string $openAppid 开放平台帐号appid
     * @return bool|string
     */
    public function openUnbind($appid, $openAppid)
    {
        if (!$this->access_token) return false;
        $params = array(
            'appid' => $appid,
            'open_appid' => $openAppid,
        );
        $result = $this->http_post(self::API_URL_PREFIX . self::OPEN_UNBIND . 'access_token=' . $this->access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case -1:
                        throw new \Exception('系统繁忙!');
                        break;
                    case 40013:
                        throw new \Exception('appid 无效!');
                        break;
                    case 89001:
                        throw new \Exception('uthorizer与开放平台帐号主体不相同!');
                        break;
                    case 89003:
                        throw new \Exception('该开放平台帐号并非通过api创建，不允许操作!');
                        break;
                }
            }
            return $json;
        }
        return false;
    }

    /**
     * 获取公众号/小程序所绑定的开放平台帐号
     * @param  string $appid 授权公众号或小程序的 appid
     * @param  string $openAppid 开放平台帐号appid
     * @return bool|string
     */
    public function openGet($appid)
    {
        if (!$this->access_token) return false;
        $params = array(
            'appid' => $appid,
        );
        $result = $this->http_post(self::API_URL_PREFIX . self::OPEN_GET . 'access_token=' . $this->access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case -1:
                        throw new \Exception('系统繁忙!');
                        break;
                    case 40013:
                        throw new \Exception('appid 无效!');
                        break;
                    case 89002:
                        throw new \Exception('该公众号/小程序未绑定微信开放平台帐号!');
                        break;
                }
            }
            return $json;
        }
        return false;
    }

    /**
     * 申请使用插件
     * @param  string $pluginAppid 插件appid
     * @return bool|string
     */
    public function applyPlugin($pluginAppid)
    {
        if (!$this->access_token) return false;
        $params = array(
            'action' => 'apply',
            'plugin_appid' => $pluginAppid,
        );
        $result = $this->http_post(self::WXA_URL_PREFIX . self::PLUGIN . 'access_token=' . $this->access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case -1:
                        throw new \Exception('系统繁忙!');
                        break;
                    case 89236:
                        throw new \Exception('该插件不能申请!');
                        break;
                    case 89237:
                        throw new \Exception('已经添加该插件!');
                        break;
                    case 89238:
                        throw new \Exception('申请或使用的插件已经达到上限!');
                        break;
                    case 89239:
                        throw new \Exception('该插件不存在!');
                        break;
                }
            }
            return $json;
        }
        return false;
    }

    /**
     * 查询已添加的插件
     * @return bool|string
     */
    public function selectPlugin()
    {
        if (!$this->access_token) return false;
        $params = array(
            'action' => 'list',
        );
        $result = $this->http_post(self::WXA_URL_PREFIX . self::PLUGIN . 'access_token=' . $this->access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
            }
            return $json;
        }
        return false;
    }

    /**
     * 删除已添加的插件
     * @param  string $pluginAppid 插件appid
     * @return bool|string
     */
    public function deletePlugin($pluginAppid)
    {
        if (!$this->access_token) return false;
        $params = array(
            'action' => 'unbind',
            'plugin_appid' => $pluginAppid,
        );
        $result = $this->http_post(self::WXA_URL_PREFIX . self::PLUGIN . 'access_token=' . $this->access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case -1:
                        throw new \Exception('系统繁忙!');
                        break;
                    case 89243:
                        throw new \Exception('该申请为“待确认”状态，不可删除!');
                        break;
                    case 89244:
                        throw new \Exception('不存在该插件appid!');
                        break;
                }
            }
            return $json;
        }
        return false;
    }

    /**
     * 快速更新插件版本
     * @param  string $pluginAppid 插件appid
     * @param  string $userVersion 升级至版本号，要求此插件版本支持快速更新
     * @return bool|string
     */
    public function updatePlugin($pluginAppid, $userVersion)
    {
        if (!$this->access_token) return false;
        $params = array(
            'action' => 'update',
            'user_version' => $userVersion,
            'plugin_appid' => $pluginAppid,
        );
        $result = $this->http_post(self::WXA_URL_PREFIX . self::PLUGIN . 'access_token=' . $this->access_token, self::json_encode($params));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || $json['errcode'] != 0) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                switch ($this->errCode) {
                    case -1:
                        throw new \Exception('系统繁忙!');
                        break;
                    case 89256:
                        throw new \Exception('token信息有误!');
                        break;
                    case 89257:
                        throw new \Exception('该插件版本不支持快速更新!');
                        break;
                    case 89258:
                        throw new \Exception('当前小程序帐号存在灰度发布中的版本，不可操作快速更新!');
                        break;
                }
            }
            return $json;
        }
        return false;
    }


}




