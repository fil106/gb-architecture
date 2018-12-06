<?php

namespace Service\SocialNetworks;



class VkNetwork implements SocialNetwork
{
    public function getHeadScript()
    {
        return '<script type="text/javascript" src="https://vk.com/js/api/share.js?95" charset="windows-1251"></script>';
    }

    public function getButton()
    {
        return '<script type="text/javascript"><!--
document.write(VK.Share.button(false,{type: "custom", text: "<img src=\"https://vk.com/images/share_32.png\" width=\"32\" height=\"32\" />"}));
--></script>';
    }
}