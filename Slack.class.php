<?php
//Here you can add some config that will be useful for all Slack bots. For example, arrays to compare channel ID with its name
class Slack{

    public $channels_id_to_numan_name = array(
            'CHANNEL_SLACK_ID'=>'HUMAN NAME',
        );

    public $channels_id_to_slack_name = array(
            'CHANNEL_SLACK_ID'=>'SLACK_NAME',
        );

    public $channels_by_slack_name = array(
            "CHANNEL_SLACK_NAME"=>'SLACK_ID',
        );

    public $user_groups = array(
            'GROUP_HUMAN_NAME' => 'SLACK_ID',
    );

}
?>