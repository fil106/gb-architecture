<?php

declare(strict_types = 1);

namespace Service\Vkontakte;

Use VK\Client\VKApiClient;


class Vk
{
    private $vk;

    public function initialize()
    {
        $this->vk = VKApiClient::class();
    }

    public function setHeadScript()
    {
        return '<script type="text/javascript" src="https://vk.com/js/api/share.js?95" charset="windows-1251"></script>';
    }

    public function getPublishButton()
    {
        return '<script type="text/javascript">document.write(VK.Share.button(false,{type: "round", text: "Сохранить"}));</script>';
    }

}