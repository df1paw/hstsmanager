<?php
/** @var array $_ */
/** @var \OCP\IL10N $l */
?>
<div id="hsts" class="section">
    <h2><?php p($l->t('HTTP Strict Transport Security')); ?></h2>
    <p class="settings-hint">
        <?php p($l->t('Adds the Strict-Transport-Security header to HTTPS responses when the web server itself cannot be configured to do so.')); ?>
    </p>

    <?php if ($_['overriddenByConfig']): ?>
    <p class="settings-hint hsts-warning">
        <?php p($l->t('One or more of these values are set in config.php and take precedence over the settings below.')); ?>
    </p>
    <?php endif; ?>

    <p class="hsts-field">
        <label for="hsts-max-age"><?php p($l->t('Max age (seconds)')); ?></label><br>
        <input type="number" min="0" step="1" id="hsts-max-age" name="maxAge"
               value="<?php p($_['maxAge']); ?>">
    </p>

    <p class="hsts-field">
        <input type="checkbox" class="checkbox" id="hsts-include-subdomains" name="includeSubDomains"
               <?php if ($_['includeSubDomains']) {
                   p('checked');
               } ?>>
        <label for="hsts-include-subdomains"><?php p($l->t('Include subdomains')); ?></label>
    </p>

    <p class="hsts-field">
        <input type="checkbox" class="checkbox" id="hsts-preload" name="preload"
               <?php if ($_['preload']) {
                   p('checked');
               } ?>>
        <label for="hsts-preload"><?php p($l->t('Allow preload (for submission to hstspreload.org)')); ?></label>
    </p>

    <p id="hsts-status" class="hsts-status"></p>
</div>
