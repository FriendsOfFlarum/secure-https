import { settings } from '@fof-components';

const {
    SettingsModal,
    items: { BooleanItem },
} = settings;

app.initializers.add('fof/secure-https', () => {
    app.extensionSettings['fof-secure-https'] = () =>
        app.modal.show(
            SettingsModal, {
                title: app.translator.trans('fof-secure-https.admin.settings.title'),
                type: 'small',
                items: e => [
                    <BooleanItem setting={e} name="fof-secure-https.proxy" cast={Number}>
                        {app.translator.trans('fof-secure-https.admin.settings.replace')}
                    </BooleanItem>,
                ],
            }
        );
});
