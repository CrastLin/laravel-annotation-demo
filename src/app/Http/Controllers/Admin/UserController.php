<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;

/**
 * Class UserController
 * @package App\Http\Controllers\Admin
 * @node(name=用户中心, order=1)
 */
class UserController extends AdminController
{

    /**
     * @node(name=用户列表)
     * @RequestMapping("userlist")
     */
    function userList()
    {
        // todo
    }

    /**
     * @node(name=导出用户列表, parent=userList)
     * @GetMapping
     */
    function export()
    {
        // todo
    }

    /**
     * @node(name=用户详情, parent=userList)
     * @RequestMapping
     */
    function profile()
    {
        // todo
    }

    /**
     * @node(name=用户详情, parent=userList)
     * @RequestMapping
     */
    function editProfile()
    {
        // todo
    }

    /**
     * @node(name=申请列表)
     * @RequestMapping("apply_list")
     */
    function applyList()
    {
        // todo
    }
}