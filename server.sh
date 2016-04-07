export BASE_PATH='/Users/prieber/Work/Starburst Creative/'

update_site(){
    echo "updating site"
}

update_sites(){
    echo "updating sites"
}

update_config(){
    php bin/console config:update
}

new_repo(){
    php bin/console new:repo
}