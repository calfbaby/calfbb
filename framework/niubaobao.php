<?php
/**
 * lnmpbao - A PHP Framework For Web Artisans
 *
 * @package  clafbaby
 * @author   牛宝宝技术团队  <baobao@clafbaby.com>
 */
/* ========================================================================
 * niubaobao核心类
 * 实现以下几个功能
 * 类自动加载
 * 启动框架
 * 引入模型
 * 引入视图
 * ======================================================================== */

class niubaobao
{
    /**
     * model用于存放已经加载的model模型,下次加载时直接返回
     */
    public $model;
    /**
     * 视图赋值
     */
    public $assign;


    /**
     * 自动加载类
     * @param string $class 需要加载的类,需要带上命名空间
     */
    public static function load($class)
    {

        $class = lcfirst(str_replace('\\', '/', trim($class, '\\')));

        if (is_file(CORE . $class . '.php')) {

            include_once CORE . $class . '.php';
        } else {
            if (is_file(NIUBAOBAO . '/' . $class . '.php')) {

                include_once NIUBAOBAO . '/' . $class . '.php';
            }
        }
    }

    /**
     * 框架启动方法,完成了两件事情
     * 1.加载route解析当前URL
     * 2.找到对应的控制以及方法,并运行
     */
    public static function run()
    {
        global $_G;
        $request = new \Framework\library\route();

        \Framework\library\log::init();


        //如果是多模块,可以通过动态设置module的形式,动态条用不同模块
        if (@$_GET['m'] !='web' && @isset($_GET['m'])) {
            $MODULE_NAME = 'addons\\'.$_GET['m'];

        } else {
            $MODULE_NAME = $_G['config']['MASTER'];

        }

        $ctrlClass = '\\' . $MODULE_NAME . '\controller\\' . $request->ctrl ;

        $action = $request->action;
        //系统默认目录

        $ctrlFile = APP . 'controller/' . $request->ctrl . '.php';



        if (is_file($ctrlFile)) {
            include $ctrlFile;

        } else {

            if (DEBUG) {
                throw new Exception($ctrlClass . '是一个不存在的控制器');
            } else {
                show404();
            }
        }

        $ctrl = new $ctrlClass();

        //如果开启restful,那么加载方法时带上请求类型
        if (\Framework\library\conf::get('OPEN_RESTFUL', 'system')) {
            $action = strtolower($request->method()) . ucfirst($action);
        }

        $ctrl->$action();
    }


    /**
     * 引入函数类
     * @param string func 函数名
     */
    public static function loadFunc($func)
    {

            if (is_file(NIUBAOBAO . '/' . $func . '.php')) {

                include_once CORE .'function/'.$func.'.php';

            }else{

//                CORE .'function/'.$func.'.php'
            }


    }

}