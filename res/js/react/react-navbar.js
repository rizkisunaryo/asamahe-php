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
	render: function() {
		return (
      <div className="col-sm-5">
      	<div className="row">
      		<MenuIcon iconId="recentIcon" />
      		<MenuIcon iconId="hotIcon" />
      		<MenuIcon iconId="topIcon" />
      		<CreateNewJokeButton />
				</div>
			</div>
    );
	}
});

var MenuIcon = React.createClass({
	render: function() {
		var menuHlId = curPage+'Icon'==this.props.iconId? 'menuHl' : '';
		return (
      <div className="col-xs-2">
      	<div id={menuHlId} className="text-center">
					<div id={this.props.iconId} className="menuIcon"></div>
				</div>
			</div>
    );
	}
});

var CreateNewJokeButton = React.createClass({
	render: function() {
		return (
      <div className="col-xs-6">
				<div className="row">
					<div id="crtNewBtn"></div>
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
				<input id="searchTxt" type="text" size="18" placeHolder="Search..." />
				<div id="searchBtn"></div>
			</div>
    );
	}
});

var LoginButton = React.createClass({
	render: function() {
		return (
      <div id="loginBtn"></div>
    );
	}
});

React.render(
  <NavbarSection />,
  document.getElementById('navbarSection')
);