import React, {Component} from 'react';
import axios from 'axios';
import moment from 'moment';
class App extends Component {

    constructor(props) {
        super(props);
        this.state = {
            title: '',
            body: '',
            posts: [],
            loading: false
        };
        //bind
        this.handleSubmit = this.handleSubmit.bind(this);
        this.handleChange = this.handleChange.bind(this);
        this.renderPosts = this.renderPosts.bind(this);
    }

    getPosts() {
        this.setState({loading:true});
        axios.get('/posts').then((
            response //console.log(response.data.posts)
        ) => this.setState({
            posts: [...response.data.posts],
            loading: false
        })
        );
    }

    componentWillMount() {
        this.getPosts();
    }

    componentDidMount() {
        Echo.private('new-post').listen('PostCreated', (e) => {
            //console.log(e);
            //this.setState({posts: [e.post, ...this.state.posts]});
            if (window.Laravel.user.following.includes(e.post.user_id)) {
                this.setState({posts: [e.post, ...this.state.posts]});
            }
        });
        //setInterval(()=>this.getPosts(), 1000)
    }

    componentWillUnmount() {
        //clearInterval(this.interval)
    }

    handleSubmit(e) {
        e.preventDefault();
        //this.postData()
        axios.post('/posts', {
            title: this.state.title,
            body: this.state.body
        }).then(response => {
            this.setState({
                posts: [response.data, ...this.state.posts]
            });
        });
        this.setState({
            title: '',
            body: ''
        });
    }

    postData() {
        axios.post('/posts', {
            title: this.state.title,
            body: this.state.body
        });
    }

    handleChange(e) {
        this.setState({
            [e.target.name]: e.target.value,
            //body: e.target.value
        });
    }

    renderPosts(){
        const isLiked = false
        return this.state.posts.map(post => (
            <div key={post.id} className="media">
                <div className="media-left">
                    <img src={post.user.avatar} className="media-object mr-2" />
                </div>
                <div className="media-body">
                    <div className="user">
                        <a href={`/users/${post.user.name}`}>
                            <b>{post.user.name}</b>
                        </a>{' '}
                        - {moment(post.created_at).fromNow()}
                    </div>
                    <p>
                        <b>{post.title}{': '}</b>
                        {post.body}<br/>
                        
                        {isLiked ? <a href="{{ route('posts.dislike', $post.user) }}">Dislike</a> : <a href="#">Like</a>}
                    </p>
                </div>
            </div>
            ))
    }

    render() {
        return (
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-md-6">
                        <div className="card">
                            <div className="card-header">Post something</div>
                            <div className="card-body">
                                <form onSubmit={this.handleSubmit}>
                                    <div className="form-group">
                                        <input type="text" onChange={this.handleChange} name="title" value={this.state.title} className="form-control" rows="5" maxLength="50" placeholder="Title" required />
                                    </div>
                                    <div className="form-group">
                                        <textarea onChange={this.handleChange} name="body" value={this.state.body} className="form-control" rows="5" maxLength="140" placeholder="What's up?" required/>
                                    </div>
                                    <input type="submit" className="form-control" value="Post"/>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div className="col-md-6">
                        <div className="card">
                            <div className="card-header">Recent Post</div>
                            <div className="card-body">
                                {!this.state.loading ? this.renderPosts(): 'Loading'}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default App;