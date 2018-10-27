import SettingsModal from '@fof/components/admin/settings/SettingsModal';
import BooleanItem from '@fof/components/admin/settings/items/BooleanItem';

app.initializers.add('fof/secure-https', () => {
    app.extensionSettings['fof-secure-https'] = () =>
        app.modal.show(
            new SettingsModal({
                title: 'FriendsOfFlarum Secure HTTPS',
                className: 'FoFSecureHttpsSettingsModal',
                items: [
                    <BooleanItem key="fof-secure-https.proxy" cast={Number}>
                        {app.translator.trans('fof-secure-https.admin.settings.replace')}
                    </BooleanItem>,
                ],
            })
        );
});
