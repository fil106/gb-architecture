<?php

namespace Service\SocialNetworks;

interface SocialNetwork
{
    public function getHeadScript();
    public function getButton();
}