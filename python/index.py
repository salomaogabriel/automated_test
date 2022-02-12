import requests
import datetime
import csv
# data={'username': 'teste', 'pwd' : 'teste' , 'submit' : 'Log in'}

# CSV File: description , Username , password , submit , expected result 
# r = requests.post('http://localhost/automatizacaoteste/API/index.php', data)
# print(r.text)
tests_passed = 0
tests_failed = 0
# Creates a POST request and returns the server response
def post_request(username,pwd, submit):
    data = {'username': username, 'pwd' : pwd , 'submit' : submit}
    r = requests.post('http://localhost/automatizacaoteste/API/index.php', data)
    return r.text

def run_individual_test(test_case,username,pwd,submit,expected,file):
    output = post_request(username,pwd,submit)
    current_date = datetime.datetime.now()
    if output[1:-1] == expected:
        file.write( "Test Case: "+ test_case +" | Status: PASS | Server Response: "+ output +" | Date: "+ current_date.strftime("%m/%d/%Y, %H:%M:%S") +"\n")
        print("Test Case: ",test_case, " | Status: PASS | Server Response: ", output ," | Date: ", current_date)
        global tests_passed
        tests_passed += 1
    else:
        print("Test Case: ",test_case , "| Status: FAIL | Server Response: ", output , " | Expected: " , expected , " | Date: ", current_date)
        file.write("Test Case: "+ test_case +" | Status: FAIL | Server Response: "+ output + " | Expected: " + expected + " | Date: "+ current_date.strftime("%m/%d/%Y, %H:%M:%S")+"\n")
        global tests_failed 
        tests_failed += 1


def retrieve_and_run_tests():
    start_time = datetime.datetime.now()
    file_name = "log/{}.txt"
    file_name = file_name.format(start_time.strftime("%m%d%Y%H%M%S"))
    f = open(file_name, "a")
    f.write(f'Test started at {start_time}\n')
    f.write("\n")

    with open('python/tests.csv') as csv_file:
        csv_reader = csv.reader(csv_file, delimiter=',')
        line_count = 0
        for row in csv_reader:
            if line_count == 0:
                line_count += 1
            else:
                # print(f'\t{row[0]} works in the {row[1]} department, and was born in {row[2]}.')
                run_individual_test(row[0],row[1],row[2],row[3],row[4],f)
                line_count += 1
        f.write("\n")
        f.write(f'A total of {line_count - 1} tests were completed.\n')
        print(f'A total of {line_count - 1} tests were completed.')
        f.write(f'{tests_passed} tests were successfull and {tests_failed} tests failed\n')
        print(f'{tests_passed} tests were successfull and {tests_failed} tests failed')
        finish_time = datetime.datetime.now()
        print(f'Test started at {start_time}')
        print(f'Test finished at {finish_time}')
        f.write(f'Test finished at {finish_time.strftime("%m/%d/%Y, %H:%M:%S")}\n')
        f.close()

retrieve_and_run_tests()
