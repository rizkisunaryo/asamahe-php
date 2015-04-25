var NavbarSection = React.createClass({
	render: function() {
		return (
			<div>
				<NavbarHider />
				<Navbar />
			</div>
    );
	}
});

var NavbarHider = React.createClass({
	render: function() {
		return (
      <div id="navbarHider"></div>
    );
	}
});

var Navbar = React.createClass({
	render: function() {
		return (
      <div id="navbar" className="row">
      	<LogoSection />
      	<MenuSection />
      	<SearchAndLoginSection />
      </div>
    );
	}
});

var LogoSection = React.createClass({
	render: function() {
		return (
      <div className="col-sm-2">
				<div id="logoHolder">
					<div id="logo"><img src="res/img/asamahe_logo.png" /></div>
				</div>
			</div>
    );
	}
});

var MenuSection = React.createClass({
	handleClick: function(page) {
		this.setState({curPage: page});
	},
	getInitialState: function() {
		return {curPage: curPage};
  },
  componentDidMount: function() {
  	this.setState({curPage: curPage});
  },
	render: function() {
		return (
      <div className="col-sm-5">
      	<div className="row">
      		<MenuIcon page="recent" ocFunc={this.handleClick} curPage={this.state.curPage} />
      		<MenuIcon page="hot" ocFunc={this.handleClick} curPage={this.state.curPage} />
      		<MenuIcon page="top" ocFunc={this.handleClick} curPage={this.state.curPage} />
      		<CreateNewJokeButton />
				</div>
			</div>
    );
	}
});

var MenuIcon = React.createClass({
	render: function() {
		var menuHlId = this.props.curPage==this.props.page? 'menuHl' : '';
		var iconId = this.props.page+'Icon';
		var boundClick = this.props.ocFunc.bind(this, this.props.page);
		return (
      <div className="col-xs-2">
      	<div id={menuHlId} className="text-center">
					<div id={iconId} className="menuIcon btn" onClick={boundClick}></div>
				</div>
			</div>
    );
	}
});

var CreateNewJokeButton = React.createClass({
	getInitialState: function() {
    return {windowWidth: window.innerWidth};
  },
  handleResize: function(e) {
    this.setState({windowWidth: window.innerWidth});
  },
  componentDidMount: function() {
    window.addEventListener('resize', this.handleResize);
  },
  componentWillUnmount: function() {
    window.removeEventListener('resize', this.handleResize);
  },
	render: function() {
		var classRight = this.state.windowWidth<WINDOW_WIDTH_XS? 'crtNewBtnRight '+PULL_RIGHT_CLASS : 'crtNewBtnLeft';
		var allClasses = 'btn ' + classRight;
		return (
      <div className="col-xs-6">
				<div className="row">
					<div id="crtNewBtn" className={allClasses}></div>
				</div>
			</div>
    );
	}
});

var SearchAndLoginSection = React.createClass({
	render: function() {
		return (
      <div className="col-sm-5">
				<div className="row" id="rightNavHolder">
					<div className="pull-right">
						<SearchHolder />
						<LoginButton />
					</div>
				</div>
			</div>
    );
	}
});

var SearchHolder = React.createClass({
	render: function() {
		return (
      <div id="searchHolder">
				<input id="searchTxt" type="text" size="18" placeholder="Search..." />
				<div id="searchBtn" className="btn"></div>
			</div>
    );
	}
});

var LoginButton = React.createClass({
	render: function() {
		return (
      <div id="loginBtn" className="btn"></div>
    );
	}
});

React.render(
  <NavbarSection />,
  document.getElementById('navbarSection')
);