import SecureHttpsSettingsModal from './components/SecureHttpsSettingsModal';

app.initializers.add('fof/secure-https', () => {
    app.extensionSettings['fof-secure-https'] = () => app.modal.show(new SecureHttpsSettingsModal());
});
