<?php
declare (strict_types=1);

namespace Smalls\VideoTools\Tools;

use Smalls\VideoTools\Exception\ErrorVideoException;
use Smalls\VideoTools\Interfaces\IVideo;
use Smalls\VideoTools\Logic\KuaiShouLogic;

/**
 * Created By 1
 * Author：smalls
 * Email：smalls0098@gmail.com
 * Date：2020/4/27 - 0:46
 **/
class KuaiShou extends Base implements IVideo
{

    /**
     * 更新时间：2020/6/9
     * 快手会封IP，如果你是APP端的软件，建议把快手集成在本地。如果是小程序或者网页那也没办法了。
     * 你有什么办法也可以进行自己封装
     * @param string $url
     * @return array
     * @throws ErrorVideoException
     */
    public function start(string $url): array
    {
        $this->logic = new KuaiShouLogic($url);
        $this->logic->checkUrlHasTrue();
        $this->logic->setContents();
        $this->logic->formatData();
        return $this->exportData();
    }

}