<?php

namespace Smalls\VideoTools\Logic;

use Smalls\VideoTools\Enumerates\UserGentType;
use Smalls\VideoTools\Exception\ErrorVideoException;
use Smalls\VideoTools\Traits\HttpRequest;
use Smalls\VideoTools\Utils\CommonUtil;

/**
 * Created By 1
 * Author：smalls
 * Email：smalls0098@gmail.com
 * Date：2020/6/9 - 16:41
 **/
class QuanMingGaoXiaoLogic
{

    use HttpRequest;

    private $url;
    private $contentId;

    private $contents;

    /**
     * QuanMingGaoXiaoLogic constructor.
     * @param $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    public function checkUrlHasTrue()
    {
        if (empty($this->url)) {
            throw new ErrorVideoException("url cannot be empty");
        }

        if (strpos($this->url, "longxia.music.xiaomi.com") == false) {
            throw new ErrorVideoException("there was a problem with url verification");
        }
    }

    public function setContentId()
    {
        preg_match('/video\/([0-9]+)\?/i', $this->url, $itemMatches);
        if (CommonUtil::checkEmptyMatch($itemMatches)) {
            throw new ErrorVideoException("itemMatches parsing failed");
        }
        $this->contentId = $itemMatches[1];
        return $itemMatches[1];
    }

    public function getContents()
    {
        $contents = $this->get('https://longxia.music.xiaomi.com/api/share', [
            'contentType' => 'video',
            'contentId' => $this->contentId,
        ], [
            'User-Agent' => UserGentType::ANDROID_USER_AGENT,
        ]);

        if (isset($contents['code']) && $contents['code'] != 200) {
            throw new ErrorVideoException("contents code not 200 parsing failed");
        }
        if (empty($contents['data']['videoInfo'])) {
            throw new ErrorVideoException("contents not exist data -> videoInfo");
        }
        $this->contents = $contents['data']['videoInfo'];
    }

    /**
     * @return mixed
     */
    public function getContentId()
    {
        return $this->contentId;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    public function getVideoUrl()
    {
        return CommonUtil::getData($this->contents['videoInfo']['url']);
    }

    public function getVideoImage()
    {
        return CommonUtil::getData($this->contents['videoInfo']['coverUrl']);
    }

    public function getVideoDesc()
    {
        return CommonUtil::getData($this->contents['videoInfo']['desc']);
    }

    public function getUsername()
    {
        return CommonUtil::getData($this->contents['author']['authorName']);
    }

    public function getUserPic()
    {
        return CommonUtil::getData($this->contents['author']['authorAvatarUrl']);
    }


}