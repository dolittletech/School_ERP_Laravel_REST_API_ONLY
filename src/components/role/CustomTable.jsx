import { Button, Card, Table } from "antd";
import dayjs from "dayjs";
import { useEffect, useState } from "react";
import { useDeletePermissionsMutation } from "../../redux/rtk/features/role/roleApi";
import ColVisibilityDropdown from "../Shared/ColVisibilityDropdown";

const CustomTable = ({ role }) => {
  const [keys, setKeys] = useState([]);
  const [columnsToShow, setColumnsToShow] = useState([]);

  const columns = [
    {
      id: 1,
      title: "ID",
      dataIndex: "id",
      key: "id",
    },
    {
      id: 2,
      title: "Name",
      dataIndex: "permission",
      key: "permission",
      render: ({ name } = {}) => name,
    },
    {
      id: 3,
      title: "Created At",
      dataIndex: "createdAt",
      key: "createdAt",
      render: (createdAt) => dayjs(createdAt).format("DD/MM/YYYY"),
    },
    {
      id: 4,
      title: "Updated At",
      dataIndex: "updatedAt",
      key: "updatedAt",
      render: (updatedAt) => dayjs(updatedAt).format("DD/MM/YYYY"),
    },
  ];

  useEffect(() => {
    setColumnsToShow(columns);
  // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  const columnsToShowHandler = (val) => {
    setColumnsToShow(val);
  };

  const rowSelection = {
    onChange: (selectedRowKeys, selectedRows) => {
      setKeys(selectedRowKeys);
    },
  };
  const [deleteRolePermission, { isLoading }] = useDeletePermissionsMutation();
  const onDelete = async () => {
    try {
      const data = await deleteRolePermission(keys);
      if (data) {
        window.location.reload();
      }
    } catch (error) {
      console.log(error.message);
    }
  };

  return (
    <Card className='card-body mb-3 '>
      <div className='table-responsive'>
        <h4 className='text-center mb-2 text-2xl'> Permissions</h4>

        {keys && keys.length > 0 && (
          <div className='text-start mb-1'>
            <Button type='danger' onClick={onDelete} loading={isLoading}>
              Delete
            </Button>
          </div>
        )}
        {columns.length > 0 && (
          <div style={{ marginBottom: "30px" }}>
            <ColVisibilityDropdown
              options={columns}
              columns={columns}
              columnsToShowHandler={columnsToShowHandler}
            />
          </div>
        )}
        <Table
          rowSelection={columnsToShow.length > 0 && rowSelection}
          columns={columnsToShow}
          dataSource={role}
          pagination={{
            pageSize: 20,
            showSizeChanger: true,
            pageSizeOptions: ["10", "20", "50"],
          }}
          rowKey={(record) => record.id}
        />
      </div>
    </Card>
  );
};

export default CustomTable;
