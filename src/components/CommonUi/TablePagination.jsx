import { Pagination, Table } from "antd";
import { useEffect, useState } from "react";
import { CSVLink } from "react-csv";
import UserPrivateComponent from "../PrivateRoutes/UserPrivateComponent";
import ColVisibilityDropdown from "../Shared/ColVisibilityDropdown";

const TablePagination = ({
  columns,
  list,
  total,
  setPageConfig,
  loading,
  csvFileName,
  children,
  permission,
}) => {
  const fetchData = (page, count) => {
    if (setPageConfig) {
      setPageConfig((prev) => {
        return { ...prev, page, count };
      });
    }
  };

  // column select
  const [columnsToShow, setColumnsToShow] = useState([]);
  useEffect(() => {
    setColumnsToShow(columns);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);
  const columnsToShowHandler = (val) => {
    setColumnsToShow(val);
  };

  return (
    <>
      <UserPrivateComponent permission={permission}>
        <div className='mt-2'>
          <div className='pb-3'>
            <div className='w-full dark:text-yellow-50 flex flex-col md:flex-row gap-2 items-center justify-between'>
              <div className='flex gap-2'>
                <div className='px-4 py-1 bg-black/80 text-white border rounded-md'>
                  <CSVLink
                    data={list ? list : ""}
                    className='text-white text-xs  md:text-base  py-1 px-0 rounded mr-2 '
                    filename={csvFileName}
                  >
                    Download CSV
                  </CSVLink>
                </div>
                <ColVisibilityDropdown
                  options={columns}
                  columns={columns}
                  columnsToShowHandler={columnsToShowHandler}
                />
              </div>

              <div className=''>
                {total >= 1 && (
                  <Pagination
                    total={total}
                    showTotal={(total, range) =>
                      `${range[0]}-${range[1]} of ${total} items`
                    }
                    onChange={fetchData}
                    defaultPageSize={10}
                    defaultCurrent={1}
                    showSizeChanger={total > 10}
                  />
                )}
              </div>
            </div>
          </div>

          <Table
            loading={loading}
            columns={columnsToShow}
            dataSource={
              !!list?.length && list.map((item) => ({ ...item, key: item?.id }))
            }
            pagination={false}
            scroll={{ x: 1000, y: window.innerHeight - 0 }}
          />
        </div>
        {children && children}
      </UserPrivateComponent>
    </>
  );
};
export default TablePagination;
