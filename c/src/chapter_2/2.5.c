#include <stdio.h>

int main(void)
{
	int cookies = 5;
	int cookie_calories = 125;
	int total_eaten = 0;

	int eaten = 2;
	cookies = cookies - eaten;
	total_eaten = total_eaten + eaten;
	printf("\n I have eaten %d cookies. There are %d cookies left", eaten, cookies);

	eaten = 3;
	cookies = cookies - eaten;
	total_eaten = total_eaten + eaten;
	printf("\n I hava eaten %d more . Now there are %d cookies left\n", eaten, cookies);
	printf("\n Total energry consumed is %d calories. \n", total_eaten*cookie_calories);
	return 0;
}
