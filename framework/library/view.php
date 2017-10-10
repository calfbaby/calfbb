<?php

namespace  Framework\library;

trait view
{
    public $assign="";
    /**
     * 初始化产量
     */
    public function __construct()
    {
        global $_G,$_GPC;
        $this->assign['_G']=$_G;
        $this->assign['_GPC']=$_GPC;
        $this->assign['APP_URL']=$_G['APP_URL'];
        $this->assign['APP']=$_G['APP'];
    }

    /**
     * 为模板对象赋值
     */
    public function assign($name, $data)
    {
        $this->assign[$name] = $data;
    }

    /**
     * 用于在控制器中加载一个模板文件
     */
    public function display($file,$module="")
    {
        $module= $module !="" ? $module  : APP;
        //echo $module . 'template/' . $file;exit;
        if (is_file($module . 'template/' . $file)) {
            \Twig_Autoloader::register();
            $loader = new \Twig_Loader_Filesystem($module . 'template/');
            $twig = new \Twig_Environment($loader, [
                'cache' => NIUBAOBAO . '/data/cache/'.MODULE.'/template',
                'debug' => DEBUG,
            ]);

            $template = $twig->loadTemplate($file);
            $template->display($this->assign ? $this->assign : []);
        } else {
            if (DEBUG) {
                throw new \Exception($file . '是一个不存在的模板文件');
            } else {
                show404();
            }
        }
    }
}


class views{
    use view;
}
