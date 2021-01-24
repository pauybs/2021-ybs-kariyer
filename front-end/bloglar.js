import React, { Component, Fragment } from 'react';
import { injectIntl } from 'react-intl';
import { Row,Card,CardBody,Jumbotron,Button, FormGroup, Label ,Badge } from 'reactstrap';
import { Colxx, Separator } from '../../components/common/CustomBootstrap';
import Breadcrumb from '../../containers/navs/Breadcrumb';
import SEO from "./seo";
import Pagination from "../../containers/pages/Pagination";
import ListPageHeading from "../../containers/pages/ListPageHeading";
import { NavLink } from "react-router-dom";
import { apiUrl } from "../../constants/defaultValues";
function collect(props) {
  return { data: props.data };
}

const ResponsiveEllipsis = responsiveHOC()(LinesEllipsis)
class Sorular extends Component {
  constructor(props) {
    super(props);
    this.mouseTrap = require('mousetrap');


    this.state = {
      selectedPageSize: 8,
      pageSizes: [8, 12, 24],
      dropdownSplitOpen: false,
      modalOpen: false,
      currentPage: 1,
      totalItemCount: 0,
      totalPage: 1,
      search: "",
      selectedItems: [],
      lastChecked: null,
      isLoading: false
    };
  }
  componentDidMount() {
    this.dataListRender();
    this.mouseTrap.bind(["ctrl+a", "command+a"], () =>
      this.handleChangeSelectAll(false)
    );
    this.mouseTrap.bind(["ctrl+d", "command+d"], () => {
      this.setState({
        selectedItems: []
      });
      return false;
    });
  }
  
  componentWillUnmount() {
    this.mouseTrap.unbind("ctrl+a");
    this.mouseTrap.unbind("command+a");
    this.mouseTrap.unbind("ctrl+d");
    this.mouseTrap.unbind("command+d");
  }


  toggleModal = () => {
    this.setState({
      modalOpen: !this.state.modalOpen
    });
  };

  changeOrderBy = column => {
    this.setState(
      {
        selectedOrderOption: this.state.orderOptions.find(
          x => x.column === column
        )
      },
      () => this.dataListRender()
    );
  };
  changePageSize = size => {
    this.setState(
      {
        selectedPageSize: size,
        currentPage: 1
      },
      () => this.dataListRender()
    );
  };
  changeDisplayMode = mode => {
    this.setState({
      displayMode: mode
    });
    return false;
  };
  onChangePage = page => {
    this.setState(
      {
        currentPage: page
      },
      () => this.dataListRender()
    );
  };

  onSearchKey = value => {
    
    //if (e.key === "Enter") {
      
      this.setState(
        {
          search: value
        },
        () => this.dataListRender()
      );
   //  }
  };


 

  dataListRender() {
    const {
      selectedPageSize,
      currentPage,
      selectedOrderOption,
      search
    } = this.state;
    
     var client = require('../../client');
     client.get("list-blog?page="+currentPage+"&search="+search+"&pageSize="+selectedPageSize)
      .then(res => {
        return res.data;
      })
     
      .then(data => {
        console.log(data.pageCount);
        this.setState({
          totalPage: data.pageCount,
          items: data.data,
          selectedItems: [],
          totalItemCount: data.total,
          isLoading: true
        });
      })
      .catch(err=> {
        this.setState({
          
          items: [],
          isLoading: true
        });
      });
  }

  onContextMenuClick = (e, data, target) => {
    console.log(
      "onContextMenuClick - selected items",
      this.state.selectedItems
    );
    console.log("onContextMenuClick - action : ", data.action);
  };

  onContextMenu = (e, data) => {
    const clickedProductId = data.data;
    if (!this.state.selectedItems.includes(clickedProductId)) {
      this.setState({
        selectedItems: [clickedProductId]
      });
    }

    return true;
  };

  
  render() {
    const {
      currentPage,
      items,
      displayMode,
      selectedPageSize,
      totalItemCount,
      selectedOrderOption,
      selectedItems,
      orderOptions,
      pageSizes,
      modalOpen,
      categories
    } = this.state;
    const { match } = this.props;
    const startIndex = (currentPage - 1) * selectedPageSize;
    const endIndex = currentPage * selectedPageSize;

    return !this.state.isLoading ? (
      <div className="loading" />
    ) : (
      <Fragment>
         <SEO 
 title="Blog Yazıları" 
 description="Yönetim Bilişim Sistemleri blog yazıları, kariyer fırsatları."
 />
        <Breadcrumb heading="Blog" match={this.props.match}/>
        <br></br>
          {localStorage.getItem('user') ? 
           <NavLink to='/blog-yaz'>
           <Button  outline color="secondary" className="mb-2 m-1">
                 Blog Yaz
               </Button>
             </NavLink>  
        : <NavLink to='/user/login'>
        <Button  outline color="secondary" className="mb-2 m-1">
              Blog Yazabilmek İçin Giriş Yapmanız Gerekiyor
            </Button>
          </NavLink>   }
        <div className="disable-text-selection">
          <ListPageHeading
            heading=" "
            displayMode={displayMode}
            changeDisplayMode={this.changeDisplayMode}
            handleChangeSelectAll={this.handleChangeSelectAll}
            changeOrderBy={this.changeOrderBy}
            changePageSize={this.changePageSize}
            selectedPageSize={selectedPageSize}
            totalItemCount={totalItemCount}
            selectedOrderOption={selectedOrderOption}
            match={match}
            startIndex={startIndex}
            endIndex={endIndex}
            selectedItemsLength={selectedItems ? selectedItems.length : 0}
            itemsLength={items ? items.length : 0}
            onSearchKey={this.onSearchKey}
            orderOptions={orderOptions}
            pageSizes={pageSizes}
            toggleModal={this.toggleModal}
          />
      
          <Row>
            
          {
                        this.state.items.map((blogItem, index) => {
                            return (
                                <Colxx xxs="12" lg="6" className="mb-5" key={index}>
                                    <Card className="flex-row listing-card-container" style={{height:195}}>
                                        <div className="w-40 position-relative">
                                            <NavLink to={"/blog/"+blogItem.slug}>
                                                <img className="card-img-left" src={apiUrl+"/blog/"+blogItem.imageHome} alt="Card cap" />
                                                {
                                                    blogItem.badge &&
                                                    (
                                                        <Badge color="primary" pill className="position-absolute badge-top-left">
                                                            {blogItem.badge}
                                                        </Badge>
                                                    )
                                                }
                                            </NavLink>
                                        </div>
                                        <div className="w-60 d-flex align-items-center">
                                            <CardBody>
                                                <NavLink  to={"/blog/"+blogItem.slug}>
                                                    <ResponsiveEllipsis
                                                        className="mb-3 listing-heading"
                                                        text={blogItem.blogTitle}
                                                        maxLine='2'
                                                        trimRight={true}
                                                        basedOn='words'
                                                        component="h5" />
                                                </NavLink>
                                                <ResponsiveEllipsis
                                                    className="listing-desc text-muted"
                                                    text={blogItem.blogContent.replace(/(<([^>]+)>)/gi, "").slice(0,160)}
                                                    maxLine='3'
                                                    trimRight={true}
                                                    basedOn='words'
                                                    component="p" />
                                            </CardBody>
                                        </div>
                                    </Card>
                                </Colxx>
                            )
                        })
                    }
            {this.state.items.length < 1 ? "Veri Bulunamadı"  : null}
            {" "}
            <Pagination
              currentPage={this.state.currentPage}
              totalPage={this.state.totalPage}
              onChangePage={i => this.onChangePage(i)}
            />
            
          </Row>
        </div>
      </Fragment>
    );
  }
}
export default injectIntl(Sorular);
