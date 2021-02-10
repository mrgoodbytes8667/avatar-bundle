# config/packages/liip_imagine.yaml
# See dos how to configure the bundle: https://symfony.com/doc/current/bundles/LiipImagineBundle/basic-usage.html
liip_imagine:
    # valid drivers options include "gd" or "gmagick" or "imagick"
    driver: "gd"

    # configure resolvers
    resolvers:

        # setup the default resolver
        default:

            # use the default web path
            web_path: ~

    # your filter sets are defined here
    filter_sets:

        # use the default cache configuration
        cache: ~

        ###> Begin Avatar thumbnail filter sets
<?php foreach ($gravatarSizes as $size): ?>
        # <?= $size['x'] ?> x <?= $size['y'] ?> avatar thumbnail "filter set"
        avatar_thumb_<?= $size['x'] ?>x<?= $size['y'] ?>:

            data_loader: gravatar

            # list of transformations to apply (the "filters")
            filters:

                # create a thumbnail: set size to <?= $size['x'] ?>x<?= $size['y'] ?> and use the "outbound" mode
                # to crop the image when the size ratio of the input differs
                thumbnail: { size: [<?= $size['x'] ?>, <?= $size['y'] ?>], mode: outbound }

                # use and setup the "strip" filter
                strip: ~
<?php endforeach; ?>
        ###< End Avatar thumbnail filter sets

    loaders:
        gravatar:
            stream:
                wrapper: '<?= $url ?>'