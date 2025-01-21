let
  unstable = import (fetchTarball https://nixos.org/channels/nixos-unstable/nixexprs.tar.xz) { };
in
{ pkgs ? import <nixpkgs> {} }:

pkgs.mkShell {
  buildInputs = with pkgs; [
    docker-compose
    envsubst
    gnused
    kubectl
    nodejs_22
    openssl
    procps
    unstable.php84
    unstable.php84Packages.composer
  ];


  shellHook = ''
    # setup aliases
    alias dc="docker-compose -f docker-compose.local.yml"
    alias dcbp="docker-compose -f docker-compose.local.yml build platform.test"
    alias dcup="docker-compose -f docker-compose.local.yml up -d"
    alias dcd="docker-compose -f docker-compose.local.yml down"
    alias dil="docker image ls"
    alias dirm="docker image rm"
    alias dvl="docker volume ls"
    alias dvrm="docker volume rm"
    alias kcd="kubectl -n iris-accessibility-development"
    alias kcs="kubectl -n iris-accessibility-staging"
    alias kcp="kubectl -n iris-accessibility-production"

    # make sure kube directory is available for setting up config
    if [[ ! -d "~/.kube" ]]; then
      mkdir -p ~/.kube
    fi

    # setup environment file
    if [[ ! -f ".env" ]]; then
      export CIPHERSWEET_KEY=$(openssl rand -hex 32)
      export DB_PASSWORD=$(openssl rand -hex 16)
      export DB_ROOT_PASSWORD=$(openssl rand -hex 24)
      export REDIS_PASSWORD=$(openssl rand -hex 20)
      export APP_KEY=$(php artisan key:generate --show)
      export WWWUSER=$UID
      envsubst < .env.local.template > .env
    fi

    # install composer packages if missing
    if [[ ! -d "vendor" ]]; then
      composer install
    fi

    # install node modules if missing
    if [[ ! -d "node_modules" ]]; then
      npm ci
    fi
  '' + (if pkgs.system == "x86_64-linux" then ''
    # setup rootless docker sock path
    alias dstart="dockerd-rootless&"
    alias dstop="pkill dockerd"
    echo -e "If using dockerd-rootless then run the following so that docker commands will run on the right socket.\nexport DOCKER_HOST=unix://$XDG_RUNTIME_DIR/docker.sock"
    echo -e "\nYou will also want to make sure that you are allowed to expose priveleged ports https://github.com/rootless-containers/rootlesskit/blob/master/docs/port.md#exposing-privileged-ports"
    ''
    else '''');
}
