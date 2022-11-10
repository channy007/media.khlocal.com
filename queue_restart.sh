# sudo supervisorctl reread
 
# sudo supervisorctl update

# ## Start process
# sudo supervisorctl start video-downloader-queue:*

sudo supervisorctl stop media-horizon:*
sudo supervisorctl reread media-horizon:*
sudo supervisorctl update media-horizon:*
sudo supervisorctl start media-horizon:*
