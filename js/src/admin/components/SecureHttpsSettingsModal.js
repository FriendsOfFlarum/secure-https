import { extend } from 'flarum/extend';
import SettingsModal from 'flarum/components/SettingsModal';
import Switch from 'flarum/components/Switch';

export default class SecureHttpsSettingsModal extends SettingsModal {
    className() {
        return 'FoFSecureHttpsSettingsModal Modal--small';
    }

    title() {
        return app.translator.trans('fof-secure-https.admin.settings.title');
    }

    form() {
        return [
            <div className="Form-group">
                {Switch.component({
                    state: !!Number(this.setting('fof-secure-https.proxy')()),
                    children: app.translator.trans('fof-secure-https.admin.settings.replace'),
                    onchange: this.setting('fof-secure-https.proxy'),
                })}
            </div>,
        ];
    }
}
