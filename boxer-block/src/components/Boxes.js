const { Component, Fragment } = wp.element;
const { withSelect } = wp.data;
import { isUndefined, pickBy } from 'lodash'

class Boxes extends Component {

    constructor() {
        super(...arguments);
        this.state = {
        };
    }

    render() {
        const {
            alignment,
            backgroundColor,
            latestPosts,
        } = this.props;

        const hasPosts = Array.isArray(latestPosts) && latestPosts.length;
        return (
            <Fragment>
                {
                    hasPosts ?
                        <ul className="boxer" >
                            {
                                latestPosts.map((post, i) => {
                                    if (post) {
                                        const dateString = post.date ? new Date(post.date).toDateString().substring(4, 15) : '';
                                        const markup = { __html: post.content.rendered };
                                        return (
                                            <li key={i} style={{ textAlign: alignment, backgroundColor: backgroundColor }} >
                                                <a className="title" href={post.link}>{post.title.rendered}</a>
                                                <p className="content" dangerouslySetInnerHTML={markup}></p>
                                                <span className="date">{dateString}</span>
                                            </li>
                                        );
                                    }
                                })
                            }
                        </ul>
                        :
                        <div>Boxer is useless without posts.</div>
                }
            </Fragment>
        );
    }
}

export default withSelect((select, props) => {
    const {
        displayNumber = 3
    } = props;
    const { getPosts } = select('boxer')
    return {
        latestPosts: getPosts(displayNumber),
    }
})(Boxes)
