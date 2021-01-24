import React from "react";
import {
  Row,
  Card,
  CardBody,
  CardSubtitle,
  CardImg,
  CardText,
  CustomInput,
  Badge,
  Button
} from "reactstrap";
import { NavLink } from "react-router-dom";
import classnames from "classnames";
import { ContextMenuTrigger } from "react-contextmenu";
import { Colxx } from "../../components/common/CustomBootstrap";

const AdminImageListView = ({ product, isSelect, collect, onCheckItem }) => {
  return (
    
    <Colxx sm="6" lg="4" xl="3" className="mb-3" key={product.slug}>
      
      <ContextMenuTrigger id="menu_id" data={product.slug} collect={collect}>
      <NavLink to={"/andac/"+product.slug}>
        <Card
          onClick={event => onCheckItem(event, product.slug)}
          className={classnames({
            active: isSelect
          })}
        >
         
          
          
          <CardBody>
            <Row>
              
            
              <Colxx xxs="12" className="mb-3">
               
                <CardSubtitle>{product.universityName}</CardSubtitle>
                <CardText className="text-muted text-small mb-0 font-weight-light">
                <CardImg top alt={product.universityLogo} src={product.universityLogo} />
                </CardText>
               
                <Button  outline color="secondary" className="mb-2 m-1">
                 Mezun Listesi
                </Button>
               
              </Colxx>
            </Row>
          </CardBody>
        </Card>
        </NavLink>
      </ContextMenuTrigger>
    </Colxx>
   
  );
};

export default React.memo(AdminImageListView);
