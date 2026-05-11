FROM php:8.5-cli

ARG USERNAME=vscode
ARG USER_UID=1000
ARG USER_GID=1000
ARG NODE_VERSION=24

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        ca-certificates \
        curl \
        git \
        gnupg \
        less \
        nano \
        openssh-client \
        postgresql-client \
        sudo \
        unzip \
        zip \
    && install-php-extensions bcmath exif gd pcntl pdo_pgsql redis zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && curl -fsSL "https://deb.nodesource.com/setup_${NODE_VERSION}.x" | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && groupadd --gid "${USER_GID}" "${USERNAME}" \
    && useradd --uid "${USER_UID}" --gid "${USER_GID}" --create-home --shell /bin/bash "${USERNAME}" \
    && echo "${USERNAME} ALL=(root) NOPASSWD:ALL" > "/etc/sudoers.d/${USERNAME}" \
    && chmod 0440 "/etc/sudoers.d/${USERNAME}" \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*
RUN { \
        echo 'upload_max_filesize=10M'; \
        echo 'post_max_size=10M'; \
    } > /usr/local/etc/php/conf.d/uploads.ini

WORKDIR /workspaces/art-gallery

USER ${USERNAME}
