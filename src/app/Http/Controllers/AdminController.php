<?php
/**
 * 控制器基类
 */

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

abstract class AdminController extends Controller implements \LaravelAnnotationNodeInterface
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected
        /**
         * @var string $userId
         */
        $userId,
        /**
         * @var string $appName
         */
        $appName,
        /**
         * @var string $controller
         */
        $controller,
        /**
         * @var string $action
         */
        $action,
        /**
         * @var string $accessPath 访问权限路径
         */
        $accessPath,
        /**
         * @var array $ignoreList 忽略权限验证列表
         */
        $ignoreList = ['Admin/User/login'];

    /**
     * @var array 输出的数据
     */
    protected $data = array();
    /**
     * @var \Illuminate\Http\Request 请求对象
     */
    protected $request;
    /**
     * @var Response 响应
     */
    protected $response;
    /**
     * @var AdminAccess $access
     */
    protected $access;


    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->response = response();
        $this->currentControllerAction();
        $this->_initialize();
    }

    protected function _initialize()
    {
    }

    /**
     * 获取当前控制器和方法名称
     */
    protected function currentControllerAction()
    {
        list($controller, $action) = explode('@', $this->request->route()->getActionName());
        $controllerSpaces = explode('\\', $controller);
        $controller = array_pop($controllerSpaces);
        $appName = array_pop($controllerSpaces);
        $this->appName = $appName;
        $this->controller = $controller;
        $this->action = $action;
        $this->accessPath = "{$this->appName}/{$this->controller}/{$this->action}";
    }

    /**
     * acl 路由访问权限控制
     * @throws Throwable
     */
    private function AclAccess()
    {
        // 超级管理员时，执行保存节点
        if (!$this->checkAccess())
            return responseJson(500, '您没有访问权限');
        return true;
    }


    /**
     * check current admin access
     * @return bool
     * @throws Throwable
     */
    protected function checkAccess(): bool
    {
        if ($this->userId == 1)
            return true;
        if (!empty($this->ignoreList) && in_array($this->accessPath, $this->ignoreList))
            return true;
        if (!$this->access)
            $this->access = AdminAccess::single($this->userId, $this->accessPath);
        return $this->access->check();
    }

    /**
     * @node (parent=0, menu=1, auth=0, order=0)
     */
    function defaultPage()
    {
        return responseJson(404, '页面不存在');
    }


    /**
     * Execute an action on the controller.
     *
     * @param string $method
     * @param array $parameters
     * @return Response
     * @throws Throwable
     */
    public function callAction($method, $parameters)
    {
        // 验证权限
        $this->AclAccess();
        $this->userId = $this->request->offsetGet('user_id');
        return call_user_func_array([$this, $method], $parameters);
    }

}
