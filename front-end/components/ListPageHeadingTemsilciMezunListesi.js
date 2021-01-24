import React, { Component } from "react";
import {
  Row,
  Button,
  ButtonDropdown,
  UncontrolledDropdown,
  DropdownMenu,
  DropdownItem,
  DropdownToggle,
  CustomInput,
  Collapse
} from "reactstrap";
import { injectIntl } from "react-intl";

import { Colxx, Separator } from "../../components/common/CustomBootstrap";
import Breadcrumb from "../navs/Breadcrumb";
import IntlMessages from "../../helpers/IntlMessages";

class ListPageHeading extends Component {
  constructor(props) {
    super();
    this.state = {
      dropdownSplitOpen: false,
      displayOptionsIsOpen: false
    };
  }

  toggleDisplayOptions = () => {
    this.setState(prevState => ({
      displayOptionsIsOpen: !prevState.displayOptionsIsOpen
    }));
  };
  toggleSplit =()=> {
    this.setState(prevState => ({
      dropdownSplitOpen: !prevState.dropdownSplitOpen
    }));
  }

  render() {
    const { messages } = this.props.intl;
    const {
      displayMode,
      changeDisplayMode,
      handleChangeSelectAll,
      changeOrderBy,
      changePageSize,
      selectedPageSize,
      totalItemCount,
      selectedOrderOption,
      match,
      startIndex,
      endIndex,
      selectedItemsLength,
      itemsLength,
      onSearchKey,
      orderOptions,
      pageSizes,
      toggleModal,
      heading,
      
    } = this.props;

    const { displayOptionsIsOpen, dropdownSplitOpen } = this.state;
    return (
      <Row>
        <Colxx xxs="12">
          <div className="mb-2">
            <h1>
              <IntlMessages id={heading} />   
              
                 <Button
                 href={match.url+'/istatistik'}
                        color="primary"
                        className={`btn-shadow btn-multiple-state`}
                        size="lg"
                      >
                        <span className="spinner d-inline-block">
                          <span className="bounce1" />
                          <span className="bounce2" />
                          <span className="bounce3" />
                        </span>
                        <span className="label">İstatistikler</span>
                      </Button>
            </h1>

            
            <Breadcrumb match={match} />
          </div>

          <div className="mb-2">
            <Button
              color="empty"
              className="pt-0 pl-0 d-inline-block d-md-none"
              onClick={this.toggleDisplayOptions}
            >
              Fİltreler
              <i className="simple-icon-arrow-down align-middle" />
            </Button>
            <Collapse
              isOpen={displayOptionsIsOpen}
              className="d-md-block"
              id="displayOptions">
              <span className="mr-3 d-inline-block float-md-left">
              
              </span>

              <div className="d-block d-md-inline-block pt-1">
              
                <div className="search-sm d-inline-block float-md-left mr-1 mb-1 align-top">
                  <input
                    type="text"
                    name="keyword"
                    id="search"
                    placeholder="Ara"
                    onKeyPress={e => onSearchKey(e.target.value)}
                  />
                </div>
 
              </div>
           
              <div className="float-md-right pt-1">
                <span className="text-muted text-small mr-1">{`${startIndex}-${endIndex} kayıttan ${totalItemCount} gösteriliyor. `}</span>
                <UncontrolledDropdown className="d-inline-block">
                  <DropdownToggle caret color="outline-dark" size="xs">
                    {selectedPageSize}
                  </DropdownToggle>
                  <DropdownMenu right>
                    {pageSizes.map((size, index) => {
                      return (
                        <DropdownItem
                          key={index}
                          onClick={() => changePageSize(size)}
                        >
                          {size}
                        </DropdownItem>
                      );
                    })}
                  </DropdownMenu>
                </UncontrolledDropdown>
              </div>
            </Collapse>
          </div>
          <Separator className="mb-5" />
        </Colxx>
      </Row>
    );
  }
}

export default injectIntl(ListPageHeading);
