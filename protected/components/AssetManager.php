<?php
/**
 * CAssetManager class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */


/**
 * CAssetManager is a Web application component that manages private files (called assets) and makes them accessible by Web clients.
 *
 * It achieves this goal by copying assets to a Web-accessible directory
 * and returns the corresponding URL for accessing them.
 *
 * To publish an asset, simply call {@link publish()}.
 *
 * The Web-accessible directory holding the published files is specified
 * by {@link setBasePath basePath}, which defaults to the "assets" directory
 * under the directory containing the application entry script file.
 * The property {@link setBaseUrl baseUrl} refers to the URL for accessing
 * the {@link setBasePath basePath}.
 *
 * @property string $basePath The root directory storing the published asset files. Defaults to 'WebRoot/assets'.
 * @property string $baseUrl The base url that the published asset files can be accessed.
 * Note, the ending slashes are stripped off. Defaults to '/AppBaseUrl/assets'.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id$
 * @package system.web
 * @since 1.0
 */
class AssetManager extends CAssetManager
{
    public $remote_server;
    public $remote_port = 22;
    public $remote_path;
    public $username;
    public $password;
    public $staticUrl;
    public $staticSubdomain;
    public $additionalSync = array();

    protected $_ssh;

    public function init()
    {
        $this->staticUrl = rtrim(Yii::app()->createAbsoluteUrl('/', array()), '/');

        if($this->staticSubdomain) {
            $this->staticUrl = preg_replace('#(?<=/)(project.com|project.ru)#',
                $this->staticSubdomain.'.$1', $this->staticUrl);
        }

        $this->baseUrl = $this->staticUrl . '/assets';
    }

    public function getAssetUrl($path)
    {
        return Yii::app()->assetManager->staticUrl . '/' . ltrim($path, '/') . '?v=' . Yii::app()->params['vAssets'];
    }


}
