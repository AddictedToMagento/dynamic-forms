<?php
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'AddictedToMagento_DynamicForms',
    implode(DIRECTORY_SEPARATOR, [__DIR__, 'src'])
);
