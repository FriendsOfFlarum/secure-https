import { extend } from 'flarum/extend';
import CommentPost from 'flarum/components/CommentPost';
import PostImage from "./components/PostImage";

app.initializers.add('fof/secure-https', () => {
    const regExp = /<img src="http:\/\/(.+)" title="(.*)" alt="(.*)">/g;

    extend(CommentPost.prototype, 'init', function() {
        const content = this.props.post.contentHtml();
        const proxy = app.forum.attribute('fof-secure-https.proxy');

        if (proxy) {
            let match;
            let newContent = content;

            while ((match = regExp.exec(content)) !== null) {
                match[1] = encodeURIComponent(match[1]);
                match[1] = match[1].replace('%2F', '%252F'); //Apache Support

                newContent = newContent.replace(
                    match[0],
                    `<img src="${app.forum.attribute('apiUrl')}/fof/secure-https/${match[1]}" title="${match[2]}" alt="${match[3]}">`
                );
            }

            this.props.post.contentHtml = m.prop(newContent);
        } else {
            let match;
            let newContent = content;
            //
            // while ((match = regExp.exec(content))) {
            //     const url = match[1];
            //     const item = <PostImage url={match[1]} title={match[2]} alt={match[3]} />
            //
            //     newContent = newContent.replace(
            //         match[0],
            //         `<${item.tag} ${Object.entries(item).map(([key, value]) => `${key}=${value}`)}>`
            //     )
            // }
            //
            // this.props.post.contentHtml = m.prop(newContent);

            // this.props.post.contentHtml = m.prop(
            //     //I know this is a mess, this is the shortest way to achieve this. There's a TON of escaping that happened lol
            //     this.props.post.contentHtml().replace(
            //         /<img src="http:\/\/(.+)" title="(.*)" alt="(.*)">/g,
            //         // console.log(<img src="https://$1" />)
            //         '<img onerror="$(this).next().empty().append(\'<blockquote style=&#92;&#39;background-color: #c0392b; color: white;&#92;&#39; class=&#92;&#39;uncited&#92;&#39;><div><p>'+app.translator.trans('fof-secure-https.forum.removed')+'| <a href=&#92;&#39;#&#92;&#39; style=&#92;&#39;color:white;&#92;&#39;onclick=&#92;&#39;window.open(&#92;&#34;http://{@src}&#92;&#34;,&#92;&#34;name&#92;&#34;,&#92;&#34;width=600,height=400&#92;&#34;)&#92;&#39;>'+app.translator.trans('fof-secure-https.forum.show')+'</a></p></div></blockquote>\');$(this).hide();" onload="$(this).next().empty();" class="securehttps-replaced" src="https://$1" title="{@title}" alt="{@alt}"><span><br><br><i class="icon fa fa-spinner fa-spin fa-3x fa-fw"></i> Loading Image<br></span>'
            //     )
            // );
        }
    });
});
