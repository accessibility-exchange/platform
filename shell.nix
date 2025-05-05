{
  pkgs ? import <nixpkgs> { },
}:

pkgs.mkShell {
  buildInputs = with pkgs; [
    docker-compose
    envsubst
    gnused
    kubectl
    nodejs_22
    openssl
    procps
    php84
    php84Packages.composer
  ];

  shellHook =
    ''
      # =====================
      # == Docker Compose ==
      # =====================
      dc() {
          docker-compose "$@"
      }
      alias dcbp='dc build platform.test'
      alias dcupd='dc up -d'
      alias dcdn='dc down'

      dex() {
        dc exec "$@"
      }
      dexit() {
        dc exec -it "$@"
      }
      alias dexp='dexit --user www-data platform.test bash'

      # ===================
      # == Docker Images ==
      # ===================

      img() {
        docker image "$@"
      }

      imglsp() {
        img ls --format '{{.Repository}}:{{.Tag}}' 'platform*' "$@"
      }
      alias imgls='img ls'
      alias imgrmp="imglsp | xargs -r -p docker image rm"
      alias imgprune="img prune -af"

      # Shortcuts for manual removal
      alias imgrm='img rm'

      # =================
      # == Docker Logs ==
      # =================

      # Logs
      log() {
        docker logs "$@"
      }
      logf() {
        log -f "$@"
      }
      alias logt='logf -n 100 platform.test'
      alias logp='logf -n 100 platform.proxy'
      alias logsql='logf -n 100 platform.mysql'
      alias tailt='log -n 100 platform.test'
      alias tailp='log -n 100 platform.proxy'
      alias tailsql='log -n 100 platform.mysql'

      # ====================
      # == Docker Volumes ==
      # ====================

      vol() {
        docker volume "$@"
      }

      volsp() {
        vol ls --format '{{.Name}}' | grep -E 'platform\.(meilisearch|mysql|redis|test)$'
      }

      alias vols='vol ls'
      alias volrmp="volsp | xargs -r -p docker volume rm"
      alias volrm='vol rm'
      alias volprune="vol prune -af"


      # =============
      # == Laravel ==
      # =============

      artisan() {
        dex --user www-data platform.test php artisan "$@"
      }

      comp() {
        dex --user www-data platform.test composer "$@"
      }

      phpstan() {
        dex --user www-data platform.test ./vendor/bin/phpstan "$@"
      }

      alias analyze="comp analyze && phpstan analyze"
      alias pint="dex --user www-data platform.test ./vendor/bin/pint"
      alias tinker='artisan tinker'
      alias test='artisan test'


      # ==================
      # == Kube Control ==
      # ==================

      alias kflushd="kflush development"
      alias kflushs="kflush staging"
      alias kflushp="kflush production"

      kflush() {
        namespace="$1"
        if [ -z "$namespace" ]; then
          echo "Namespace is required"
          return 1
        fi

        # Get all pods matching app- prefix in the given namespace
        pods=$(kubectl get pods -n "iris-accessibility-$namespace" --field-selector=status.phase=Running -o name | grep '^pod/app-')

        # Process all pods with deploy:local
        echo "$pods" | while read -r pod; do
          echo "Running php artisan deploy:local in $pod"
          kubectl exec -n "iris-accessibility-$namespace" "$pod" -- php artisan deploy:local
        done

        # Process first pod with deploy:global
        first_pod=$(echo "$pods" | head -n 1)
        if [ -n "$first_pod" ]; then
          echo "Running php artisan deploy:global in first container ($first_pod)"
          kubectl exec -n "iris-accessibility-$namespace" "$first_pod" -- php artisan deploy:global
        fi
      }

      # Function to flush all app- pods in all environments
      kflushall() {
        namespaces=(development staging production)
        for ns in "''${namespaces[@]}"; do
          echo "Processing iris-accessibility-$ns namespace:"
          kflush $ns;
          echo
        done
      }

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
    ''
    + (
      if pkgs.system == "x86_64-linux" then
        ''
          # setup rootless docker sock path
          alias dstart="dockerd-rootless&"
          alias dstop="pkill dockerd"
          echo -e "If using dockerd-rootless then run the following so that docker commands will run on the right socket.\nexport DOCKER_HOST=unix://$XDG_RUNTIME_DIR/docker.sock"
          echo -e "\nYou will also want to make sure that you are allowed to expose priveleged ports https://github.com/rootless-containers/rootlesskit/blob/master/docs/port.md#exposing-privileged-ports"
        ''
      else
        ''''
    );
}
