import app from 'flarum/app';

app.initializers.add('fof/secure-https', () => {
  app.extensionData.for('fof-secure-https')
    .registerSetting({
      label: app.translator.trans('fof-secure-https.admin.settings.replace'),
      setting: 'fof-secure-https.proxy',
      type: 'boolean'
    });
});
