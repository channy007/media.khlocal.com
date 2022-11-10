#!/bin/bash
#usage page-id access-token source(full path) description title

facebook_link="https://graph.facebook.com/v15.0"
page_id="$1"
access_token="$2"
source="source=@$3"
description="description=$4"
title="title=$5"
url="$facebook_link/$page_id/videos?access_token=$access_token"

#upload video to facebook
curl --location --request POST $url \
--form "$source" \
--form "$description" \
--form "$title"

# upload video to facebook
# curl --location --request POST 'https://graph.facebook.com/v15.0/102318056026253/videos?access_token=EAASNg0JLXNgBADr86T01KkzaN5XOZCmZCKvkp2tGCmpCdw6J6TEQOtAzIw3dzBUsQ35qxfepTgByls8N8XtP5f9IVcybyPjVaAno8mhJinc3tZBIwKJO1Atk2BiD23H1fFObQMNKf3BVV58uhGU503NCyRwglMOC5zJCUJdZCADisLUspXbLgP9m62xZAA3YZBp54qkUFgagZDZD' \
# --form "$source"