# def count_elements(arr):
#     element_count = {}
#     for element in arr:
#         if element[1] not in element_count:
#             element_count[element[1]] = [element[0], 1]
#         else:
#             element_count[element[1]][1] += 1
#     return element_count

# def find_max_count_and_element(arr):
#     element_count = count_elements(arr)
#     max_count = 1
#     max_element = None
#     for element, count_info in element_count.items():
#         count = count_info[1]
#         if count > max_count:
#             max_count = count
#             max_element = (count_info[0], element)
#         elif count == max_count:
#             max_element = None
#     return max_count, max_element

# # 示例用法
# arr = [["192.168.11.1", "abcd"], ["192.168.11.2", "abd"], ["192.168.11.3", "abc"]]
# max_count, max_element = find_max_count_and_element(arr)
# if max_element is None:
#     print("所有值的出現次數都相同。")
# else:
#     print(f"出現次數最多的值為：{max_element[1]}，出現次數：{max_count}，對應的第一個元素為：{max_element[0]}")

# # def read_txt_file(file_name):
# #     context = ""
# #     with open(file_name,"r") as f:
# #         context = f.read()
# #     return context

# # file_name = './record/1.txt'
# # content = read_txt_file(file_name)
# # print("文件内容为：", content)

from ast import literal_eval
s = "(\'172.17.0.4\', 8001)"
# port = 8001
print(s[2:12])
