let
  unstable = import (fetchTarball https://nixos.org/channels/nixos-unstable/nixexprs.tar.xz) { };
in
{ pkgs ? import <nixpkgs> {} }:

pkgs.mkShell {
  buildInputs = with pkgs; [
    docker
    docker-compose
    envsubst
    git
    gnused
    kubectl
    nodejs_22
    openssl
    rootlesskit
    unstable.php84
    unstable.php84Packages.composer
  ];

  # Ensure Docker service is running
  shellHook = ''
    export DOCKER_HOST=unix://$XDG_RUNTIME_DIR/docker.sock

    # setup aliases
    alias dc="docker-compose -f docker-compose.local.yml"
    alias dcbp="docker-compose -f docker-compose.local.yml build platform.test"
    alias dcup="docker-compose -f docker-compose.local.yml up -d"
    alias dcd="docker-compose -f docker-compose.local.yml down"
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
        export DB_PASSWORD=$(openssl rand -hex 32)
        export REDIS_PASSWORD=$(openssl rand -hex 20)
        export APP_KEY=$(openssl rand -hex 32 | base64 -w 0)
        export WWWUSER=$UID
        export WWWGROUP=$GID
        envsubst < .env.local.template > .env
    fi

    # install composer dependencies
    composer install

    # install node modules
    npm install
  '';
}
