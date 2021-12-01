const { apiFetch } = wp;
const { registerStore } = wp.data;

const DEFAULT_STATE = {
    posts: [],
    displayNumber: undefined
};

const actions = {
    fetchFromAPI(path) {
        return {
            type: 'FETCH_FROM_API',
            path,
        };
    },

    setPosts(posts, displayNumber) {
        return {
            type: 'SET_POSTS',
            posts,
            displayNumber
        };
    },
};

registerStore('boxer', {
    reducer(state = DEFAULT_STATE, action) {

        switch (action.type) {

            case 'SET_POSTS':

                const posts = [];

                for (let j = 0; j < action.displayNumber; j++) {
                    posts.push(action.posts[j]);
                }

                for (let i = posts.length; i < state.posts.length; i++) {
                    posts.push(state.posts[i]);
                }

                return {
                    ...state,
                    posts
                };
        }

        return state;
    },

    actions,

    selectors: {
        getPosts(state, displayNumber) {
            const { posts } = state;
            return posts.slice(0, displayNumber);
        },
    },

    controls: {
        FETCH_FROM_API(action) {
            return apiFetch({ path: action.path });
        },
    },

    resolvers: {
        * getPosts(displayNumber) {
            const path = `wp/v2/posts?order=desc&orderby=date&per_page=${displayNumber}&context=view&_locale=user`;
            const posts = yield actions.fetchFromAPI(path);
            return actions.setPosts(posts, displayNumber);
        }
    },
});