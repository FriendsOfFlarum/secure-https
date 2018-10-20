import Component from 'flarum/Component';

export default class PostImage extends Component {
    view() {
        const { url, title, alt } = this.props;

        return <img src={url} title={title} alt={alt} />
    }
}